<?php

namespace Webkul\AiSupport\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\AiSupport\Repositories\AiKnowledgeBaseRepository;

class ClaudeService
{
    private const API_URL = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct(
        protected AiKnowledgeBaseRepository $knowledgeBaseRepository,
        protected OrderContextService $orderContext
    ) {}

    public function chat(array $history, array $tools = []): string
    {
        $apiKey = core()->getConfigData('ai-support.general.api_key')
            ?? env('GROQ_API_KEY');

        if (! $apiKey) {
            return 'AI support is not configured. Please contact us directly.';
        }

        $model = core()->getConfigData('ai-support.general.model') ?? 'llama-3.3-70b-versatile';

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->buildSystemPrompt()]],
            $history
        );

        $payload = [
            'model'       => $model,
            'messages'    => $messages,
            'tools'       => empty($tools) ? $this->getDefaultTools() : $tools,
            'tool_choice' => 'auto',
        ];

        return $this->callWithToolLoop($apiKey, $payload);
    }

    private function callWithToolLoop(string $apiKey, array $payload): string
    {
        $messages = $payload['messages'];

        for ($i = 0; $i < 5; $i++) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post(self::API_URL, array_merge($payload, ['messages' => $messages]));

            if ($response->failed()) {
                Log::error('Groq API error', ['status' => $response->status(), 'body' => $response->body()]);

                return 'Sorry, I\'m having trouble responding right now. Please try again.';
            }

            $data   = $response->json();
            $choice = $data['choices'][0] ?? null;

            if (! $choice) {
                return 'I was unable to process your request. Please try again.';
            }

            $finishReason = $choice['finish_reason'] ?? 'stop';
            $message      = $choice['message'];

            if ($finishReason === 'stop') {
                return $message['content'] ?? '';
            }

            if ($finishReason === 'tool_calls') {
                $toolCalls = $message['tool_calls'] ?? [];

                $messages[] = $message;

                foreach ($toolCalls as $toolCall) {
                    $name   = $toolCall['function']['name'];
                    $input  = json_decode($toolCall['function']['arguments'], true) ?? [];
                    $result = $this->executeTool($name, $input);

                    $messages[] = [
                        'role'         => 'tool',
                        'tool_call_id' => $toolCall['id'],
                        'content'      => $result,
                    ];
                }

                continue;
            }

            break;
        }

        return 'I was unable to process your request. Please try again.';
    }

    private function executeTool(string $name, array $input): string
    {
        return match ($name) {
            'get_order_status'    => $this->orderContext->getOrderStatus($input['order_id'] ?? ''),
            'get_shipping_status' => $this->orderContext->getShippingStatus($input['order_id'] ?? ''),
            'request_human_agent' => '__HANDOFF__',
            default               => 'Tool not found.',
        };
    }

    private function buildSystemPrompt(): string
    {
        $storeName    = core()->getConfigData('general.general.information.name') ?? config('app.name', 'Our Store');
        $currency     = core()->getConfigData('general.general.information.base_currency') ?? 'EGP';
        $customPrompt = core()->getConfigData('ai-support.general.system_prompt') ?? '';

        $kb        = $this->knowledgeBaseRepository->getActiveEntries();
        $kbSection = '';
        if ($kb->isNotEmpty()) {
            $kbSection = "\n\n## Knowledge Base\n";
            foreach ($kb as $entry) {
                $kbSection .= "Q: {$entry->question}\nA: {$entry->answer}\n\n";
            }
        }

        return <<<PROMPT
You are a friendly and professional customer support AI for {$storeName}, a fashion e-commerce store.
Store currency: {$currency}.

## Your Role
- Answer questions about orders, shipping, returns, and products
- Be helpful, concise, and empathetic
- If you need order details, use the available tools
- If the customer is very frustrated or the issue is complex, offer to connect them with a human agent using the request_human_agent tool
- Always respond in the same language the customer uses

## Capabilities
- Look up order status and shipping tracking in real-time
- Answer general store questions
- Help with complaints and escalations

{$customPrompt}
{$kbSection}
PROMPT;
    }

    private function getDefaultTools(): array
    {
        return [
            [
                'type'     => 'function',
                'function' => [
                    'name'        => 'get_order_status',
                    'description' => 'Get the current status and details of a customer order',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => [
                            'order_id' => [
                                'type'        => 'string',
                                'description' => 'The order ID or increment ID (e.g. "000000001")',
                            ],
                        ],
                        'required' => ['order_id'],
                    ],
                ],
            ],
            [
                'type'     => 'function',
                'function' => [
                    'name'        => 'get_shipping_status',
                    'description' => 'Get shipping and tracking information for an order',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => [
                            'order_id' => [
                                'type'        => 'string',
                                'description' => 'The order ID or increment ID',
                            ],
                        ],
                        'required' => ['order_id'],
                    ],
                ],
            ],
            [
                'type'     => 'function',
                'function' => [
                    'name'        => 'request_human_agent',
                    'description' => 'Transfer the conversation to a human support agent when the issue is complex or the customer requests it',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => [
                            'reason' => [
                                'type'        => 'string',
                                'description' => 'Reason for the handoff',
                            ],
                        ],
                        'required' => [],
                    ],
                ],
            ],
        ];
    }
}

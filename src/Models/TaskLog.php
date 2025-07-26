<?php

namespace ZealPHP\Models;

class TaskLog
{
    public int $id;
    public int $task_id;
    public int $user_id;
    public string $action;
    public ?array $old_values;
    public ?array $new_values;
    public ?string $created_at;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (($key === 'old_values' || $key === 'new_values') && is_string($value)) {
                    $this->$key = json_decode($value, true);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id ?? null,
            'task_id' => $this->task_id ?? null,
            'user_id' => $this->user_id ?? null,
            'action' => $this->action ?? '',
            'old_values' => $this->old_values ?? null,
            'new_values' => $this->new_values ?? null,
            'created_at' => $this->created_at ?? null,
        ];
    }
}

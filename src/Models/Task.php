<?php

namespace ZealPHP\Models;

class Task
{
    public int $id;
    public int $user_id;
    public string $title;
    public ?string $description;
    public string $status;
    public string $priority;
    public ?string $due_date;
    public string $created_at;
    public string $updated_at;

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
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id ?? null,
            'user_id' => $this->user_id ?? null,
            'title' => $this->title ?? '',
            'description' => $this->description,
            'status' => $this->status ?? 'pending',
            'priority' => $this->priority ?? 'medium',
            'due_date' => $this->due_date,
            'created_at' => $this->created_at ?? '',
            'updated_at' => $this->updated_at ?? '',
        ];
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        return strtotime($this->due_date) < time() && !$this->isCompleted();
    }
}
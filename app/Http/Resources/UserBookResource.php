<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // dd(is_null($this->resource));

        if (is_null($this->resource)) {
            $result['status'] = "انتخاب نشده";
            return $result;
        }
        
        $user = $this->getUser();
        $book = $this->getBook();
        $status = $this->status;

        $result = [
            'id' => $this->id,
            'user' => new UserPreviewResource($user),
            'book' => new BookPreviewResource($book),
            // 'current_page' => $this->current_page,
            // 'status' => $this->status,
            // 'last_read_at' => $this->last_read_at,
            // 'started_at' => $this->started_at,
            // 'finished_at' => $this->finished_at,
        ];

        switch ($status) {
            case 1:
              $result['status'] = "می‌خواهم بخوانم";
              break;
            case 2:
                $result['status'] = "دارم می‌خوانم";
                $result['current_page'] = $this->current_page;
                $result['started_at'] = $this->started_at;
                $result['last_read_at'] = $this->last_read_at;
                $result['progression'] = $this->getProgression();
              break;
            case 3:
                $result['status'] = "خوانده‌ام";
                $result['started_at'] = $this->started_at;
                $result['finished_at'] = $this->finished_at;
                $result['progression'] = 1;
              break;
            default:
                $result['status'] = "انتخاب نشده";
          }

        return $result;
    }
}

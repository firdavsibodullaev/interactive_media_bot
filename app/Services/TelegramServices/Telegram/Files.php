<?php


namespace App\Services\TelegramServices\Telegram;

use Illuminate\Support\Facades\Http;

/**
 * Class Files
 * @package App\Services\TelegramServices
 */
class Files
{

    /**
     * @var array
     */
    private $update;

    /**
     * Files constructor.
     * @param array $updates
     */
    public function __construct(array $updates)
    {
        $this->update = $updates;
    }

    /**
     * @param int $size
     * @return bool|array
     */
    public function getPhoto(int $size = 2)
    {
        if (isset($this->update["message"]["photo"])) {
            if ($size >= 0 && $size <= 2) {
                if (isset($this->update["message"]["photo"][$size]))
                    return $this->update["message"]["photo"][$size];
                else
                    return end($this->update["message"]["photo"]);
            } else
                die();
        }
        return false;
    }

    /**
     * @return mixed|bool
     */
    public function getAudio()
    {
        return $this->update["message"]["audio"] ?? false;
    }

    public function getVideo()
    {
        return $this->update["message"]["video"] ?? false;
    }

    /**
     * @return mixed|bool
     */
    public function getDocument()
    {
        return isset($this->update["message"]["document"]) ? $this->update["message"]["document"] : false;
    }

    /**
     * @return mixed
     */
    public function getFileId()
    {
        if (isset($this->update["message"]["photo"])) {
            return end($this->update["message"]["photo"])["file_id"];
        }
        foreach ($this->getFileTypes() as $type) {
            if (isset($this->update["message"][$type])) {
                return $this->update["message"][$type]["file_id"];
            }
        }
    }

    /**
     * @return string|bool
     */
    public function getFileType()
    {
        foreach ($this->getFileTypes() as $type) {
            if (isset($this->update["message"][$type])) {
                return $type;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        if ($this->getFileType() != "photo" && $this->getFileType() != "sticker") {
            foreach ($this->getFileTypes() as $type) {
                if (isset($this->update["message"][$type])) {
                    return $this->update["message"][$type]["mime_type"];
                }
            }
        } elseif ($this->getFileType() == "photo")
            return "photo";
        else
            return "sticker";
    }

    /**
     * @return array
     */
    private function getFileTypes(): array
    {
        return ["animation", "audio", "document", "photo", "sticker", "video", "voice", "photos"];
    }

    /**
     * @param string $name
     * @param string $path
     * @return bool|array
     */
    public function downloadFile(string $name, string $path)
    {
        $file_id = $this->getFileId();
        $response = Http::post("https://api.telegram.org/bot" . env("BOT_TOKEN") . "/getFile", [
            'file_id' => $file_id,
        ]);
        if ($response->ok()) {
            $result = json_decode($response->body(), JSON_OBJECT_AS_ARRAY)['result']['file_path'];
            $path_info = pathinfo($result);
            $extension = $path_info["extension"];
            $file_type = $path_info["dirname"];
            $src = 'https://api.telegram.org/file/bot' . env("BOT_TOKEN") . '/' . $result;
            $save_path = $name . "_" . now()->format('d-m-Y_H-i-s') . '.' . $extension;

            $this->createFolders($path);

            $dest = public_path("uploads/{$path}") . '/' . $save_path;
            $copy = copy($src, $dest);

            if ($copy) {
                return [
                    'file_type' => $file_type,
                    'file_name' => $save_path,
                ];
            }
            return "";
        }
        return false;
    }

    public function createFolders(string $path)
    {
        if (!file_exists(public_path("uploads"))) {
            mkdir(public_path("uploads"));
        }
        if (!file_exists(public_path("uploads/{$path}"))) {
            mkdir(public_path("uploads/{$path}"));
        }
    }
}

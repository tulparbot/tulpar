<?php

namespace App\Tulpar\Image;

class LeaveImageGenerator extends WelcomeImageGenerator
{
    /**
     * @param string      $username
     * @param string      $leave_text
     * @param string      $avatar_url
     * @param string|null $background_url
     * @param string      $foreground_color
     */
    public function __construct(
        public string      $username,
        public string      $leave_text,
        public string      $avatar_url,
        public string|null $background_url = null,
        public string      $foreground_color = '#ffffff',
    )
    {
        parent::__construct($this->username, $this->leave_text, $this->avatar_url, $this->background_url, $this->foreground_color);
    }
}

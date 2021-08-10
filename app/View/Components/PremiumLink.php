<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PremiumLink extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string|null $link = null,
        public string|null $text = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        $link = $this->link = $this->link ?? '#';
        $text = $this->text = $this->text ?? 'Premium';
        return view('components.premium-link', compact('link', 'text'));
    }
}

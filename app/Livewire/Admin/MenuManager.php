<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class MenuManager extends Component
{
    public array $menuItems = [];
    public array $footerLinks = [];

    public function mount()
    {
        $raw = setting('front.nav_menu', '');
        $this->menuItems = $raw ? json_decode($raw, true) : [];

        $footerRaw = setting('front.footer_links', '');
        if ($footerRaw) {
            $this->footerLinks = json_decode($footerRaw, true) ?: [];
        } else {
            $migrated = [];
            for ($i = 1; $i <= 9; $i++) {
                $label = setting('front.flink'.$i.'_label', '');
                $url = setting('front.flink'.$i.'_url', '');
                if ($label) {
                    $migrated[] = ['label' => $label, 'url' => $url];
                }
            }
            $this->footerLinks = $migrated;
        }
    }

    public function addItem()
    {
        $this->menuItems[] = ['label' => '', 'url' => '', 'active' => true, 'children' => []];
    }

    public function removeItem($index)
    {
        unset($this->menuItems[$index]);
        $this->menuItems = array_values($this->menuItems);
    }

    public function moveItemUp($index)
    {
        if ($index > 0) {
            $tmp = $this->menuItems[$index - 1];
            $this->menuItems[$index - 1] = $this->menuItems[$index];
            $this->menuItems[$index] = $tmp;
            $this->menuItems = array_values($this->menuItems);
        }
    }

    public function moveItemDown($index)
    {
        if ($index < count($this->menuItems) - 1) {
            $tmp = $this->menuItems[$index + 1];
            $this->menuItems[$index + 1] = $this->menuItems[$index];
            $this->menuItems[$index] = $tmp;
            $this->menuItems = array_values($this->menuItems);
        }
    }

    public function addChild($parentIndex)
    {
        $this->menuItems[$parentIndex]['children'][] = ['label' => '', 'url' => '', 'active' => true];
    }

    public function removeChild($parentIndex, $childIndex)
    {
        unset($this->menuItems[$parentIndex]['children'][$childIndex]);
        $this->menuItems[$parentIndex]['children'] = array_values($this->menuItems[$parentIndex]['children']);
    }

    public function moveChildUp($parentIndex, $childIndex)
    {
        $children = &$this->menuItems[$parentIndex]['children'];
        if ($childIndex > 0) {
            $tmp = $children[$childIndex - 1];
            $children[$childIndex - 1] = $children[$childIndex];
            $children[$childIndex] = $tmp;
            $children = array_values($children);
        }
    }

    public function moveChildDown($parentIndex, $childIndex)
    {
        $children = &$this->menuItems[$parentIndex]['children'];
        if ($childIndex < count($children) - 1) {
            $tmp = $children[$childIndex + 1];
            $children[$childIndex + 1] = $children[$childIndex];
            $children[$childIndex] = $tmp;
            $children = array_values($children);
        }
    }

    public function addFooterLink()
    {
        $this->footerLinks[] = ['label' => '', 'url' => ''];
    }

    public function removeFooterLink($index)
    {
        unset($this->footerLinks[$index]);
        $this->footerLinks = array_values($this->footerLinks);
    }

    public function save()
    {
        set_setting('front.nav_menu', json_encode($this->menuItems));
        set_setting('front.footer_links', json_encode($this->footerLinks));

        session()->flash('success', 'Menu updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.menu-manager');
    }
}

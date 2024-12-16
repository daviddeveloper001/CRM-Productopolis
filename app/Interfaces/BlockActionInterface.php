<?php
namespace App\Interfaces;

use App\Models\Block;

interface BlockActionInterface
{
    public function execute(Block $block): void;
}

<?php

namespace Notion\Test\Common;

use Notion\Common\Annotations;
use PHPUnit\Framework\TestCase;

class AnnotationsTest extends TestCase
{
    public function test_without_annotations(): void
    {
        $annotations = Annotations::create();

        $this->assertFalse($annotations->isBold());
        $this->assertFalse($annotations->isItalic());
        $this->assertFalse($annotations->isStrikeThrough());
        $this->assertFalse($annotations->isUnderline());
        $this->assertFalse($annotations->isCode());
        $this->assertEquals("default", $annotations->color());
    }

    public function test_bold(): void
    {
        $annotations = Annotations::create()->bold();

        $this->assertTrue($annotations->isBold());
    }

    public function test_italic(): void
    {
        $annotations = Annotations::create()->italic();

        $this->assertTrue($annotations->isItalic());
    }

    public function test_strike_through(): void
    {
        $annotations = Annotations::create()->strikeThrough();

        $this->assertTrue($annotations->isStrikeThrough());
    }

    public function test_underline(): void
    {
        $annotations = Annotations::create()->underline();

        $this->assertTrue($annotations->isUnderline());
    }

    public function test_code(): void
    {
        $annotations = Annotations::create()->code();

        $this->assertTrue($annotations->isCode());
    }

    public function test_change_color(): void
    {
        $annotations = Annotations::create()->withColor("red");

        $this->assertEquals("red", $annotations->color());
    }

    public function test_change_unsupported_color(): void
    {
        $this->expectException(\Exception::class);
        Annotations::create()->withColor("not-a-color");
    }
}
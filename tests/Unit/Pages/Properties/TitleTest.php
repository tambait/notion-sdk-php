<?php

namespace Notion\Test\Unit\Pages\Properties;

use Notion\Common\RichText;
use Notion\Pages\Properties\Title;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    public function test_create(): void
    {
        $title = Title::create("Dummy title");

        $this->assertEquals("Dummy title", $title->richTexts()[0]->text()?->content());
        $this->assertEquals("title", $title->property()->id());
        $this->assertEquals("title", $title->property()->type());
        $this->assertTrue($title->property()->isTitle());
    }

    public function test_array_conversion(): void
    {
        $array = [
            "id"    => "title",
            "type"  => "title",
            "title" => [[
                "plain_text" => "Dummy title",
                "href" => null,
                "annotations" => [
                    "bold"          => false,
                    "italic"        => false,
                    "strikethrough" => false,
                    "underline"     => false,
                    "code"          => false,
                    "color"         => "default",
                ],
                "type" => "text",
                "text" => [
                    "content" => "Dummy title",
                    "link"    => null,
                ],
            ]],
        ];

        $title = Title::fromArray($array);
        $this->assertEquals($array, $title->toArray());
    }

    public function test_string_conversion(): void
    {
        $title = Title::create("Dummy title");
        $this->assertEquals("Dummy title", $title->toString());
    }

    public function test_change_text(): void
    {
        $title = Title::create("")->withRichTexts([
            RichText::createText("Dummy title")
        ]);
        $this->assertEquals("Dummy title", $title->toString());
    }
}

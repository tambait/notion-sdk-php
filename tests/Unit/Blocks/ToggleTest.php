<?php

namespace Notion\Test\Unit\Blocks;

use Notion\Blocks\BlockFactory;
use Notion\Blocks\Toggle;
use Notion\Common\Date;
use Notion\Common\RichText;
use PHPUnit\Framework\TestCase;

class ToggleTest extends TestCase
{
    public function test_create_empty_toggle(): void
    {
        $toggle = Toggle::create();

        $this->assertEmpty($toggle->text());
        $this->assertEmpty($toggle->children());
    }

    public function test_create_from_string(): void
    {
        $toggle = Toggle::fromString("Dummy toggle.");

        $this->assertEquals("Dummy toggle.", $toggle->toString());
    }

    public function test_create_from_array(): void
    {
        $array = [
            "object"           => "block",
            "id"               => "04a13895-f072-4814-8af7-cd11af127040",
            "created_time"     => "2021-10-18T17:09:00.000Z",
            "last_edited_time" => "2021-10-18T17:09:00.000Z",
            "archived"         => false,
            "has_children"     => false,
            "type"             => "toggle",
            "toggle"        => [
                "text" => [
                    [
                        "plain_text"  => "Notion toggles ",
                        "href"        => null,
                        "type"        => "text",
                        "text"        => [
                            "content" => "Notion toggles ",
                            "link" => null,
                        ],
                        "annotations" => [
                            "bold"          => false,
                            "italic"        => false,
                            "strikethrough" => false,
                            "underline"     => false,
                            "code"          => false,
                            "color"         => "default",
                        ],
                    ],
                    [
                        "plain_text"  => "rock!",
                        "href"        => null,
                        "type"        => "text",
                        "text"        => [
                            "content" => "rock!",
                            "link" => null,
                        ],
                        "annotations" => [
                            "bold"          => true,
                            "italic"        => false,
                            "strikethrough" => false,
                            "underline"     => false,
                            "code"          => false,
                            "color"         => "red",
                        ],
                    ],
                ],
                "children" => [],
            ],
        ];

        $toggle = Toggle::fromArray($array);

        $this->assertCount(2, $toggle->text());
        $this->assertEmpty($toggle->children());
        $this->assertEquals("Notion toggles rock!", $toggle->toString());
        $this->assertFalse($toggle->block()->archived());

        $this->assertEquals($toggle, BlockFactory::fromArray($array));
    }

    public function test_error_on_wrong_type(): void
    {
        $this->expectException(\Exception::class);
        $array = [
            "object"           => "block",
            "id"               => "04a13895-f072-4814-8af7-cd11af127040",
            "created_time"     => "2021-10-18T17:09:00.000Z",
            "last_edited_time" => "2021-10-18T17:09:00.000Z",
            "archived"         => false,
            "has_children"     => false,
            "type"             => "wrong-type",
            "toggle"        => [
                "text"     => [],
                "children" => [],
            ],
        ];
        Toggle::fromArray($array);
    }

    public function test_transform_in_array(): void
    {
        $p = Toggle::fromString("Simple toggle");

        $expected = [
            "object"           => "block",
            "created_time"     => $p->block()->createdTime()->format(Date::FORMAT),
            "last_edited_time" => $p->block()->lastEditedType()->format(Date::FORMAT),
            "archived"         => false,
            "has_children"      => false,
            "type"             => "toggle",
            "toggle"        => [
                "text" => [[
                    "plain_text"  => "Simple toggle",
                    "href"        => null,
                    "type"        => "text",
                    "text"        => [
                        "content" => "Simple toggle",
                        "link" => null,
                    ],
                    "annotations" => [
                        "bold"          => false,
                        "italic"        => false,
                        "strikethrough" => false,
                        "underline"     => false,
                        "code"          => false,
                        "color"         => "default",
                    ],
                ]],
                "children" => [],
            ],
        ];

        $this->assertEquals($expected, $p->toArray());
    }

    public function test_replace_text(): void
    {
        $oldToggle = Toggle::fromString("This is an old toggle");

        $newToggle = $oldToggle->withText([
            RichText::createText("This is a "),
            RichText::createText("new toggle"),
        ]);

        $this->assertEquals("This is an old toggle", $oldToggle->toString());
        $this->assertEquals("This is a new toggle", $newToggle->toString());
    }

    public function test_append_text(): void
    {
        $oldToggle = Toggle::fromString("A toggle");

        $newToggle = $oldToggle->appendText(
            RichText::createText(" can be extended.")
        );

        $this->assertEquals("A toggle", $oldToggle->toString());
        $this->assertEquals("A toggle can be extended.", $newToggle->toString());
    }

    public function test_replace_children(): void
    {
        $nested1 = Toggle::fromString("Nested toggle 1");
        $nested2 = Toggle::fromString("Nested toggle 2");
        $toggle = Toggle::fromString("Simple toggle.")->withChildren([ $nested1, $nested2 ]);

        $this->assertCount(2, $toggle->children());
        $this->assertEquals($nested1, $toggle->children()[0]);
        $this->assertEquals($nested2, $toggle->children()[1]);
    }

    public function test_append_child(): void
    {
        $toggle = Toggle::fromString("Simple toggle.");
        $nestedToggle = Toggle::fromString("Nested toggle");
        $toggle = $toggle->appendChild($nestedToggle);

        $this->assertCount(1, $toggle->children());
        $this->assertEquals($nestedToggle, $toggle->children()[0]);
    }
}

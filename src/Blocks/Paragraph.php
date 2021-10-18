<?php

namespace Notion\Blocks;

use Notion\Common\RichText;
use SessionHandlerInterface;

class Paragraph implements BlockInterface
{
    private Block $block;
    /** @var \Notion\Common\RichText[] */
    private array $text;
    /** @var \Notion\Blocks\BlockInterface[] */
    private array $children;

    private function __construct(
        Block $block,
        array $text,
        array $children,
    ) {
        if (!$block->isParagraph()) {
            throw new \Exception("Block must be of type " . Block::TYPE_PARAGRAPH);
        }

        $this->block = $block;
        $this->text = $text;
        $this->children = $children;
    }

    public static function create(): self
    {
        $block = Block::create(Block::TYPE_PARAGRAPH);

        return new self($block, [], []);
    }

    public static function fromString($content): self
    {
        $block = Block::create(Block::TYPE_PARAGRAPH);
        $text = [ RichText::createText($content) ];

        return new self($block, $text, []);
    }

    public static function fromArray(array $array): self
    {
        $block = Block::fromArray($array);

        $text = array_map(fn($t) => RichText::fromArray($t), $array["text"]);

        $children = array_map(fn($b) => BlockFactory::fromArray($b), $array["children"]);

        return new self($block, $text, $children);
    }

    public function toArray(): array
    {
        $array = $this->block->toArray();

        $array["text"] = array_map(fn(RichText $t) => $t->toArray(), $this->text);
        $array["children"] = array_map(fn(BlockInterface $b) => $b->toArray(), $this->children);

        return $array;
    }

    public function toString(): string
    {
        $string = "";
        foreach ($this->text as $richText) {
            $string = $string . $richText->plainText();
        }

        return $string;
    }

    public function block(): Block
    {
        return $this->block;
    }

    public function withText(RichText ...$text): self
    {
        return new self($this->block, $text, $this->children);
    }

    public function appendText(RichText $text): self
    {
        $texts = $this->text;
        $texts[] = $text;

        return new self($this->block, $texts, $this->children);
    }

    public function withChildren(BlockInterface ...$children): self
    {
        $hasChildren = (count($children) > 0);

        return new self(
            $this->block->withHasChildren($hasChildren),
            $this->text,
            $children,
        );
    }

    public function appendChild(BlockInterface $child): self
    {
        $children = $this->children;
        $children[] = $child;

        return new self(
            $this->block->withHasChildren(true),
            $this->text,
            $children,
        );
    }
}
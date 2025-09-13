<?php

namespace Novay\Word\Traits;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Image;

trait MergeTrait
{
    protected PhpWord $phpWord;
    protected array $files = [];

    /**
     * Merge multiple DOCX files into single PhpWord object
     */
    public function merge(array $files, array $options = []): self
    {
        $this->files = $files;
        $this->phpWord = $this->phpWord ?? new PhpWord();

        $this->mergeFiles();

        return $this;
    }

    protected function mergeFiles(): void
    {
        if (empty($this->files)) {
            throw new \InvalidArgumentException("No files provided for merging.");
        }

        foreach ($this->files as $file) {
            $source = IOFactory::load($file);

            foreach ($source->getSections() as $section) {
                $newSection = $this->phpWord->addSection();
                $this->copySectionElements($section, $newSection);
            }
        }
    }

    protected function copySectionElements($sourceSection, $targetSection): void
    {
        foreach ($sourceSection->getElements() as $element) {
            $type = get_class($element);

            switch ($type) {
                case Text::class:
                    $targetSection->addText(
                        $element->getText(),
                        $element->getFontStyle(),
                        $element->getParagraphStyle() ?: ['spaceBefore' => 0, 'spaceAfter' => 0]
                    );
                    break;

                case TextRun::class:
                    $tr = $targetSection->addTextRun(
                        $element->getParagraphStyle() ?: ['spaceBefore' => 0, 'spaceAfter' => 0]
                    );

                    foreach ($element->getElements() as $child) {
                        if ($child instanceof Text) {
                            $tr->addText(
                                $child->getText(),
                                $child->getFontStyle(),
                                $child->getParagraphStyle() ?: ['spaceBefore' => 0, 'spaceAfter' => 0]
                            );
                        } elseif ($child instanceof Image) {
                            $tr->addImage($child->getSource(), [
                                'width' => $child->getStyle()->getWidth(),
                                'height'=> $child->getStyle()->getHeight(),
                                'wrappingStyle' => 'inline',
                            ]);
                        }
                    }
                    break;

                case Table::class:
                    $tableStyle = $element->getStyle();
                    if ($tableStyle instanceof \PhpOffice\PhpWord\Style\Table) {
                        $tableStyle->setCellMarginTop(0);
                        $tableStyle->setCellMarginBottom(0);
                    }
                    $targetSection->addTable(clone $element);
                    break;

                case Image::class:
                    $targetSection->addImage($element->getSource(), [
                        'width'  => $element->getStyle()->getWidth(),
                        'height' => $element->getStyle()->getHeight(),
                        'wrappingStyle' => 'inline',
                    ]);
                    break;
            }
        }
    }
}

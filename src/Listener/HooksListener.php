<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskClasses\Listener;

use Contao\ContentModel;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\StringUtil;
use Alpdesk\AlpdeskClasses\Model\AlpdeskClassesModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\RequestStack;

class HooksListener
{
    private ScopeMatcher $scopeMatcher;
    private RequestStack $requestStack;

    /**
     * @param ScopeMatcher $scopeMatcher
     */
    public function __construct(ScopeMatcher $scopeMatcher, RequestStack $requestStack)
    {
        $this->scopeMatcher = $scopeMatcher;
        $this->requestStack = $requestStack;
    }

    /**
     * @return bool
     */
    private function isFrontend(): bool
    {
        return $this->scopeMatcher->isFrontendRequest($this->requestStack->getCurrentRequest());
    }

    public function onParseTemplate(Template $objTemplate): void
    {
        if (
            $this->isFrontend() &&
            $objTemplate->type === 'article' &&
            (int)$objTemplate->hasAlpdeskclass === 1
        ) {

            if ($objTemplate->alpdeskclass !== null && $objTemplate->alpdeskclass !== '') {

                $classes = StringUtil::deserialize($objTemplate->alpdeskclass);
                if (\is_array($classes) && \count($classes) > 0) {

                    foreach ($classes as $classid) {

                        if ($classid !== '') {

                            $classObject = AlpdeskClassesModel::findById((int)$classid);
                            if ($classObject !== null) {
                                $objTemplate->class .= ' ' . $classObject->classvalue;
                            }

                        }

                    }

                }

            }

        }

    }

    public function onGetContentElement(ContentModel $element, string $buffer, $el): string
    {
        if (
            $this->isFrontend() &&
            (int)$element->hasAlpdeskclass === 1
        ) {

            if ($element->alpdeskclass !== null && $element->alpdeskclass != '') {

                $classes = StringUtil::deserialize($element->alpdeskclass);
                if (\is_array($classes) && \count($classes) > 0) {

                    $classesToAppend = [];
                    foreach ($classes as $classid) {

                        if ($classid !== '') {

                            $classObject = AlpdeskClassesModel::findById((int)$classid);
                            if ($classObject !== null) {
                                $classesToAppend[] = $classObject->classvalue;
                            }

                        }

                    }

                    if (\count($classesToAppend) > 0) {

                        $finalClasses = \implode(' ', $classesToAppend);
                        $buffer = \preg_replace_callback('|<([a-zA-Z0-9]+)(\s[^>]*?)?(?<!/)>|', function ($matches) use ($finalClasses) {
                            $tag = $matches[1];
                            $attributes = $matches[2];
                            $attributes = preg_replace('/class="([^"]+)"/', 'class="$1 ' . $finalClasses . '"', $attributes, 1, $count);
                            if (0 === $count) {
                                $attributes .= ' class="' . $finalClasses . '"';
                            }
                            return "<{$tag}{$attributes}>";
                        }, $buffer, 1);
                    }

                }

            }

        }

        return $buffer;

    }

}

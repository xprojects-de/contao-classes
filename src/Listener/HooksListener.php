<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskClasses\Listener;

use Contao\ContentModel;
use Contao\StringUtil;
use Alpdesk\AlpdeskClasses\Model\AlpdeskClassesModel;
use Contao\Template;

class HooksListener {

  public function onParseTemplate(Template $objTemplate) {

    if (TL_MODE == 'FE' && $objTemplate->type == 'article' && $objTemplate->hasAlpdeskclass == 1) {

      if ($objTemplate->alpdeskclass !== null && $objTemplate->alpdeskclass !== '') {
        $classes = StringUtil::deserialize($objTemplate->alpdeskclass);
        if (\count($classes) > 0) {
          foreach ($classes as $classid) {
            if ($classid !== '') {
              $classObject = AlpdeskClassesModel::findById(intval($classid));
              if ($classObject !== null) {
                $objTemplate->class .= ' ' . $classObject->classvalue;
              }
            }
          }
        }
      }
    }
  }

  public function onGetContentElement(ContentModel $element, string $buffer, $el): string {

    if (TL_MODE == 'FE' && $element->hasAlpdeskclass == 1) {

      $animationCss = $element->alpdeskclass;

      if ($element->alpdeskclass !== null && $element->alpdeskclass != '') {
        $classes = StringUtil::deserialize($element->alpdeskclass);
        if (\count($classes) > 0) {
          $classesToAppend = [];
          foreach ($classes as $classid) {
            if ($classid !== '') {
              $classObject = AlpdeskClassesModel::findById(intval($classid));
              if ($classObject !== null) {
                \array_push($classesToAppend, $classObject->classvalue);
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

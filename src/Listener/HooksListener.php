<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskClasses\Listener;

use Contao\ArticleModel;
use Contao\ContentModel;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\StringUtil;
use Alpdesk\AlpdeskClasses\Model\AlpdeskClassesModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class HooksListener
{
    private ScopeMatcher $scopeMatcher;
    private RequestStack $requestStack;

    /**
     * @param ScopeMatcher $scopeMatcher
     * @param RequestStack $requestStack
     */
    public function __construct(ScopeMatcher $scopeMatcher, RequestStack $requestStack)
    {
        $this->scopeMatcher = $scopeMatcher;
        $this->requestStack = $requestStack;
    }

    public function onGetArticle(ArticleModel $articleModel): void
    {
        if (!$this->requestStack->getCurrentRequest() instanceof Request) {
            return;
        }

        if (!$this->scopeMatcher->isFrontendRequest($this->requestStack->getCurrentRequest())) {
            return;
        }

        if ((int)$articleModel->hasAlpdeskclass === 1) {

            $alpdeskClasses = $articleModel->alpdeskclass ?? null;

            if (\is_string($alpdeskClasses) && $alpdeskClasses !== '') {

                $classes = StringUtil::deserialize($alpdeskClasses, true);
                if (\count($classes) > 0) {

                    $customClasses = [];

                    foreach ($classes as $classId) {

                        $classObject = AlpdeskClassesModel::findById((int)$classId);
                        if ($classObject !== null) {
                            $customClasses[] = ($classObject->classvalue ?? '');
                        }

                    }

                    if (\count($customClasses) > 0) {

                        $articleModelClasses = StringUtil::deserialize($articleModel->cssID, true);
                        $articleModelClasses[1] .= ' ' . \implode(' ', $customClasses);
                        $articleModel->cssID = \serialize($articleModelClasses);

                    }

                }

            }

        }

    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->scopeMatcher->isFrontendRequest($request)) {
            return;
        }

        if (!$request->attributes->has('contentModel')) {
            return;
        }

        $contentModel = $request->attributes->get('contentModel');

        if (!$contentModel instanceof ContentModel) {
            $contentModel = ContentModel::findByPk($contentModel);
        }

        if ((int)$contentModel->hasAlpdeskclass === 1) {

            $alpdeskClasses = $contentModel->alpdeskclass ?? null;

            if ($alpdeskClasses !== null && $alpdeskClasses !== '') {

                $classes = StringUtil::deserialize($alpdeskClasses);
                if (\is_array($classes) && \count($classes) > 0) {

                    $customClasses = [];

                    foreach ($classes as $classId) {

                        if ($classId !== '') {

                            $classObject = AlpdeskClassesModel::findById((int)$classId);
                            if ($classObject !== null) {
                                $customClasses[] = $classObject->classvalue;
                            }

                        }

                    }

                    if (\count($customClasses) > 0) {

                        $response = $event->getResponse();
                        $content = $response->getContent();

                        $finalClasses = \implode(' ', $customClasses);

                        $content = \preg_replace_callback('|<([a-zA-Z0-9]+)(\s[^>]*?)?(?<!/)>|', static function ($matches) use ($finalClasses) {

                            [$tag, $attributes] = $matches;

                            $attributes = \preg_replace('/class="([^"]+)"/', 'class="$1 ' . $finalClasses . '"', $attributes, 1, $count);
                            if (0 === $count) {
                                $attributes .= ' class="' . $finalClasses . '"';
                            }

                            return "<{$tag}{$attributes}>";

                        }, $content, 1);

                        $response->setContent($content);

                    }

                }

            }

        }

    }

}

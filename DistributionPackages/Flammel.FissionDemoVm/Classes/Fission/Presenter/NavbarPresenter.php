<?php
namespace Flammel\FissionDemoVm\Fission\Presenter;

use Flammel\Fission\Service\FissionContext;
use Flammel\Fission\ValueObject\WrappedNode;
use Flammel\Fission\Zweig\TemplatePath\NeosNamingConventionTemplatePath;
use Flammel\Zweig\Component\ComponentArguments;
use Flammel\Zweig\Component\ComponentContext;
use Flammel\Zweig\Component\ComponentName;
use Flammel\Zweig\Presenter\Presentable;
use Flammel\Zweig\Presenter\Presenter;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;

class NavbarPresenter implements Presenter
{
    /**
     * @Flow\Inject
     * @var FissionContext
     */
    protected $fissionContext;

    /**
     * @param ComponentName $name
     * @param ComponentArguments $arguments
     * @return Presentable
     */
    public function present(ComponentName $name, ComponentArguments $arguments): Presentable
    {
        return new Presentable(
            new NeosNamingConventionTemplatePath($name),
            new ComponentContext(['elements' => $this->getElements()])
        );
    }

    /**
     * @return array
     */
    private function getElements(): array
    {
        $site = $this->fissionContext->getSiteNode();
        $document = $this->fissionContext->getDocumentNode();
        $elements = [];
        /** @var NodeInterface $child */
        foreach ($site->findChildNodes() as $child) {
            if ($this->showInNav($child)) {
                $subNav = $this->getSubNav($child, $document);
                $active = $child === $document;
                $elements[] = [
                    'node' => new WrappedNode($child),
                    'children' => $subNav[0],
                    'active' => $subNav[1] || $active,
                ];
            }
        }
        return $elements;
    }

    /**
     * @param NodeInterface $page
     * @param NodeInterface $currentDocument
     * @return array
     */
    private function getSubNav(NodeInterface $page, NodeInterface $currentDocument): array
    {
        $elements = [];
        $someActive = false;
        /** @var NodeInterface $child */
        foreach ($page->findChildNodes() as $child) {
            if ($this->showInNav($child)) {
                $active = $child === $currentDocument;
                $someActive = $someActive || $active;
                $elements[] = [
                    'node' => new WrappedNode($child),
                    'active' => $active,
                ];
            }
        }
        return [$elements, $someActive];
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    private function showInNav(NodeInterface $node): bool
    {
        return $node->isVisible()
            && !$node->isHiddenInIndex()
            && $node->isAccessible()
            && $node->getNodeType()->isOfType('Neos.Neos:Document');
    }
}

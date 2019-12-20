<?php
namespace Flammel\FissionDemoVm\Fission\Functions\Demo;

use Flammel\Fission\Service\FissionContext;
use Flammel\Fission\ValueObject\WrappedNode;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;

class Nav
{
    /**
     * @Flow\Inject
     * @var FissionContext
     */
    protected $fissionContext;

    /**
     * @param WrappedNode $documentNode
     * @return array
     */
    public function getData(WrappedNode $documentNode)
    {
        return ['elements' => $this->getElements($documentNode->unwrap())];
    }

    /**
     * @param NodeInterface $document
     * @return array
     */
    private function getElements(NodeInterface $document): array
    {
        $site = $this->fissionContext->getSiteNode();
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

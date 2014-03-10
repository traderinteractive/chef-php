<?php
namespace DominionEnterprises\Chef;

use Jenssegers\Chef\Chef as ChefApi;

class Chef
{
    /**
     * @var \Jenssegers\Chef\Chef The chef API
     */
    private $_chef;

    /**
     * Construct the Chef Wrapper
     *
     * @param \Jenssegers\Chef\Chef $chef The chef API
     */
    public function __construct(ChefApi $chef)
    {
        $this->_chef = $chef;
    }

    /**
     * Updates the data bag item overwriting existing fields with the data provided.
     *
     * @param string $name The name of the data bag.
     * @param string $item The name of the item in the data bag.
     * @param array $data Fields to update in the data bag with their data.
     *
     * @return void
     */
    public function patchDatabag($name, $item, array $data)
    {
        $itemUrl = '/data/' . rawurlencode($name) . '/' . rawurlencode($item);
        $data += (array)$this->_chef->get($itemUrl);

        $this->_chef->put($itemUrl, $data);
    }

    /**
     * Gets the list of nodes.
     *
     * @return array The names of the nodes registered in chef.
     */
    public function getNodes()
    {
        return array_keys((array)$this->_chef->get('/nodes'));
    }

    /**
     * Delete the given node.
     *
     * @param string $node The name of the node to delete.
     *
     * @return void
     */
    public function deleteNode($node)
    {
        $this->_chef->delete('/nodes/' . rawurlencode($node));
    }
}

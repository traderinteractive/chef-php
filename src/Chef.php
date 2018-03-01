<?php
namespace DominionEnterprises\Chef;

use Jenssegers\Chef\Chef as ChefApi;

class Chef
{
    /**
     * @var \Jenssegers\Chef\Chef The chef API
     */
    private $chef;

    /**
     * Construct the Chef Wrapper
     *
     * @param \Jenssegers\Chef\Chef $chef The chef API
     */
    public function __construct(ChefApi $chef)
    {
        $this->chef = $chef;
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
        $data += (array)$this->chef->get($itemUrl);

        $this->chef->put($itemUrl, $data);
    }

    /**
     * Gets the list of nodes.
     *
     * @param string $role The role to query.  If unset, returns all nodes.
     *
     * @return array The names of the nodes registered in chef.
     */
    public function getNodes($role = null)
    {
        if ($role === null) {
            return array_keys((array)$this->chef->get('/nodes'));
        }

        $getNodeName = function ($node) {
            return isset($node->name) ? $node->name : null;
        };

        return array_filter(
            array_map(
                $getNodeName,
                (array)$this->chef->api('/search/node', 'GET', ['q' => "role:{$role}"])->rows
            )
        );
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
        $this->chef->delete('/nodes/' . rawurlencode($node));
    }
}

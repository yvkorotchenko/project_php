<?php

class Router
{
    private array $groupes = [];
    private array $trees = [];
    private int $current_tree;

    public function createRootGroupe(int $id, string $label = "", array $children = []) {
        echo count($this->groupes) . '</br>';
        if (empty($children)) echo 'Arr empty';
        $this->groupes[] = [
            'id' => $id,
            'label' => $label,
            'children' => $children,
        ];
        echo '</br> after = ' . count($this->groupes);
    }

    public function setTree($id) {
        $this->trees[$id] = [];
    }

    public function groupeJSON() {
        print_r('<pre>' . json_encode($this->groupes) . '<pre>');
    }
}

$r = new Router();
$r->createRootGroupe(7, 'Game settings');
$r->groupeJSON();
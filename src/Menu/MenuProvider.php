<?php

namespace App\Menu;

use App\Repository\CategoryRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;

class MenuProvider implements MenuProviderInterface
{

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @param FactoryInterface $factory
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(FactoryInterface $factory, CategoryRepository $categoryRepository)
    {
        $this->factory = $factory;
        $this->categoryRepository = $categoryRepository;
    }

    public function mainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $categoriesMenu = $menu->addChild('Категории', [
            'attributes' => [
                'dropdown' => true,
            ],
        ]);

        foreach ($this->categoryRepository->findAll() as $category) {
            $categoriesMenu->addChild($category->getName(), [
                'route' => 'category_item',
                'routeParameters' => ['id' => $category->getId()]
            ]);
        }

        $menu->addChild('Контакты', ['route' => 'feedback']);
        $menu->addChild('О магазине', ['route' => 'about']);

        return $menu;
    }

    /**
     * Retrieves a menu by its name
     *
     * @param string $name
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function get($name, array $options = array())
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException('Menu "' . $name . '" doesn\'t exists.');
        }

        return $this->mainMenu($options);
    }

    /**
     * Checks whether a menu exists in this provider
     *
     * @param string $name
     * @param array $options
     *
     * @return boolean
     */
    public function has($name, array $options = array())
    {
        if ($name == 'main') {
            return true;
        }

        return false;
    }
}

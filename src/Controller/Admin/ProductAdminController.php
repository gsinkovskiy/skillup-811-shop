<?php

namespace App\Controller\Admin;

use App\Entity\AttributeValue;
use App\Entity\Product;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductAdminController extends CRUDController
{

    public function attributesAction($id)
    {
        /** @var Product $product */
        $product = $this->admin->getSubject();

        if (!$product) {
            throw new NotFoundHttpException(sprintf('Unable to find the Product with id: %s', $id));
        }

        $form = $this->getAttributesForm($product);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $existingObject = $this->admin->update($product);

            return $this->redirectTo($existingObject);
        }

        return $this->renderWithExtraParams('admin/product/attributes.html.twig', [
            'form' => $form->createView(),
            'action' => 'attributes',
            'object' => $product,
        ]);
    }

    private function getAttributesForm(Product $product): FormInterface
    {
        $formBuilder = $this->createFormBuilder($product);

        foreach ($product->getCategory()->getAttributes() as $attribute) {
            if (!$product->getAttributeValues()->containsKey($attribute->getId())) {
                $attributeValue = new AttributeValue();
                $attributeValue->setAttribute($attribute);
                $product->addAttributeValue($attributeValue);
            }

            $formBuilder->add('attribute' . $attribute->getId(), TextType::class, [
                'property_path' => 'attributeValues[' . $attribute->getId() . '].value',
                'label' => $attribute->getName(),
            ]);
        }

        return $formBuilder->getForm();
    }

}

<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(' Parts Comment')
            ->setEntityLabelInPlural('Parts Comments')
            ->setSearchFields(['autor', 'text', 'email'])
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {

        yield AssociationField::new('parts');
        yield TextField::new('autor');
        yield EmailField::new('email');
        yield TextareaField::new('text')
            ->hideOnIndex()
        ;
        yield ImageField::new('photoFilename')
            ->setBasePath('/uploads/photos')
            ->setLabel('Photo')
        ;

        yield TextField::new('state');

        $createdAt = DateTimeField::new('createdAt')->setFormTypeOptions([
            'years' => range(date('Y'), date('Y') + 5),
            'widget' => 'single_text',
        ]);
        if (Crud::PAGE_EDIT === $pageName) {
            yield $createdAt->setFormTypeOption('disabled', true);
        } 
    }

}

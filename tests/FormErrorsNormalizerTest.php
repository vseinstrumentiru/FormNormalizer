<?php

declare(strict_types=1);

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Max107\FormNormalizer\Tests;

use Max107\FormNormalizer\FormErrorsNormalizer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class FormErrorsNormalizerTest extends TypeTestCase
{
    protected function getExtensions()
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
        ];
    }

    public function testSerializer()
    {
        $normalizer = new FormErrorsNormalizer();

        $this->assertFalse($normalizer->supportsNormalization(new \stdClass()));

        $form = $this
            ->factory
            ->createBuilder()
            ->add('username', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->getForm();

        $form->submit([]);
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());

        $this->assertSame([
            'username' => [
                'This value should not be blank.',
            ],
        ], $normalizer->normalize($form));

        $this->assertSame([
            'username' => [
                'This value should not be blank.',
            ],
        ], $normalizer->normalize($form->getErrors(true, false)));
    }
}

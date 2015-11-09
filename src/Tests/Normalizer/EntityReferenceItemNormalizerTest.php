<?php

/**
 * @file
 * Contains \Drupal\relaxed\Tests\Normalizer\EntityReferenceItemNormalizerTest.
 */

namespace Drupal\relaxed\Tests\Normalizer;

use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
/**
 * Tests the content serialization format.
 *
 * @group relaxed
 */
class EntityReferenceItemNormalizerTest extends NormalizerTestBase {

  public static $modules = array('system', 'serialization', 'user', 'node', 'comment', 'key_value', 'multiversion', 'rest', 'relaxed', 'entity_test');

  protected $entityClass = 'Drupal\entity_test\Entity\EntityTest';

  protected function setUp() {
    parent::setUp();
    $this->installSchema('system', 'sequences');

    User::create(array(
      'uid' => 0,
      'name' => '',
      'status' => 0,
    ))->save();

    $this->user = User::create([
      'name' => $this->randomMachineName(),
    ]);
    $this->user->save();

    $this->node = Node::create([
      'title' => $this->randomMachineName(),
      'type' => 'page',
      'uid' => $this->user->id(),
    ]);
    $this->node->save();

  }

  public function testNormalize() {
    //$normalized = $this->serializer->normalize($this->node->user);
    //$this->assertTrue($this->node->user InstanceOf \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem);
    //$this->expectOutputString('foo');

  }

  public function testDenormalize() {
      $this->assertTrue(true);
  }

}

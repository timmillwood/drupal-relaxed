<?php

/**
 * @file
 * Contains \Drupal\relaxed\Tests\LocalDocResourceTest.
 */

namespace Drupal\relaxed\Tests;

/**
 * Tests the /db/doc resource.
 *
 * @group relaxed
 */
class LocalDocResourceTest extends ResourceTestBase {

  public function testHead() {
    $db = $this->workspace->id();

    // HEAD and GET is handled by the same resource.
    $this->enableService('relaxed:local:doc', 'GET');
    $entity_types = ['entity_test_local'];
    foreach ($entity_types as $entity_type) {
      // Create a user with the correct permissions.
      $permissions = $this->entityPermissions($entity_type, 'view');
      $permissions[] = 'restful get relaxed:local:doc';
      $account = $this->drupalCreateUser($permissions);
      $this->drupalLogin($account);

      $entity = $this->entityTypeManager->getStorage($entity_type)->create();
      $entity->save();
      $this->httpRequest("$db/_local/" . $entity->uuid(), 'HEAD', NULL);
      $this->assertHeader('content-type', $this->defaultMimeType);
      $this->assertResponse('200', 'HTTP response code is correct.');
    }

    // Test with an entity type that is not local.
    $entity = $this->entityTypeManager->getStorage('entity_test_rev')->create();
    $entity->save();
    $this->httpRequest("$db/_local/" . $entity->uuid(), 'HEAD', NULL);
    $this->assertHeader('content-type', $this->defaultMimeType);
    $this->assertResponse('400', 'HTTP response code is correct.');
  }

  public function testGet() {
    $db = $this->workspace->id();

    $this->enableService('relaxed:local:doc', 'GET');
    $entity_types = ['entity_test_local'];
    foreach ($entity_types as $entity_type) {
      // Create a user with the correct permissions.
      $permissions = $this->entityPermissions($entity_type, 'view');
      $permissions[] = 'restful get relaxed:local:doc';
      $account = $this->drupalCreateUser($permissions);
      $this->drupalLogin($account);

      $entity = $this->entityTypeManager->getStorage($entity_type)->create();
      $entity->save();
      $this->httpRequest("$db/_local/" . $entity->uuid(), 'GET', NULL);
      $this->assertResponse('200', 'HTTP response code is correct.');
    }

    // Test with an entity type that is not local.
    $entity = $this->entityTypeManager->getStorage('entity_test_rev')->create();
    $entity->save();
    $this->httpRequest("$db/_local/" . $entity->uuid(), 'GET', NULL);
    $this->assertHeader('content-type', $this->defaultMimeType);
    $this->assertResponse('400', 'HTTP response code is correct.');
  }

  public function testPut() {
    $db = $this->workspace->id();

    $this->enableService('relaxed:local:doc', 'PUT');
    $serializer = $this->container->get('serializer');
    $entity_types = ['entity_test_local'];
    foreach ($entity_types as $entity_type) {
      // Create a user with the correct permissions.
      $permissions = $this->entityPermissions($entity_type, 'create');
      $permissions[] = 'restful put relaxed:local:doc';
      $account = $this->drupalCreateUser($permissions);
      $this->drupalLogin($account);

      $entity = $this->entityTypeManager->getStorage($entity_type)->create(['user_id' => $account->id()]);
      $serialized = $serializer->serialize($entity, $this->defaultFormat);
      $this->httpRequest("$db/_local/" . $entity->uuid(), 'PUT', $serialized);
      $this->assertResponse('201', 'HTTP response code is correct');
    }

    // Test with an entity type that is not local.
    $entity = $this->entityTypeManager->getStorage('entity_test_rev')->create();
    $serialized = $serializer->serialize($entity, $this->defaultFormat);
    $this->httpRequest("$db/_local/" . $entity->uuid(), 'PUT', $serialized);
    $this->assertHeader('content-type', $this->defaultMimeType);
    $this->assertResponse('400', 'HTTP response code is correct.');
  }

}

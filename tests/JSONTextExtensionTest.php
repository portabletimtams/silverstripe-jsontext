<?php

/**
 * @package silverstripe-jsontext
 * @subpackage fields
 * @author Russell Michell <russ@theruss.com>
 */

use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\CMS\Controllers\CMSMain;
use PhpTek\JSONText\Exception\JSONTextException;
use PhpTek\JSONText\Dev\Fixture\MyAwesomeJSONPage;

class JSONTextExtensionTest extends FunctionalTest
{
    /**
     * @var string
     */
    protected static $extra_dataobjects = [
        MyAwesomeJSONPage::class,
    ];

    /**
     * @var string
     */
    protected static $fixture_file = __DIR__ . '/fixtures/yml/JSONTextExtension.yml';

    /**
     * Is an exception thrown when no POSTed vars are available for
     * non DB-backed fields declared on a SiteTree class?
     */
    public function testExceptionThrownOnBeforeWrite()
    {
        $member = $this->objFromFixture(Member::class, 'admin');
        $fixture = $this->objFromFixture(MyAwesomeJSONPage::class, 'dummy');
        $cmsBase = CMSMain::singleton()->Link();

        $this->logInAs($member);
        Config::modify()->set(MyAwesomeJSONPage::class, 'json_field_map', [
            'MyJSON' => ['FooField'],
        ]);
        $fixture->write();

        // Submit a CMS POST request _without_ JSON data
        $this->expectException(JSONTextException::class);
        $this->post(Controller::join_links($cmsBase, 'edit/EditForm/', $fixture->ID), [
            'ParentID' => '0',
            'action_save' => 'Saved',
            'ID' => $fixture->ID,
        ]);
    }
}

<?php namespace Toyi\LaravelIdentifier\Tests;

class IdentifierTest extends TestCase
{
    protected IdentifierModel $foo;
    protected IdentifierModel $bar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->foo = new IdentifierModel();
        $this->foo->identifier = 'foo';
        $this->foo->other_key = 'hello';
        $this->foo->name = 'Foo';
        $this->foo->save();

        $this->bar = new IdentifierModel();
        $this->bar->identifier = 'bar';
        $this->bar->other_key = 'world';
        $this->bar->name = 'Bar';
        $this->bar->save();
    }

    public function test_model_can_be_retrived_with_identifier()
    {
        $model = IdentifierModel::getModelByIdentifier('bar');

        $this->assertTrue($model instanceof IdentifierModel);
        $this->assertTrue($model == 'bar');
        $this->assertTrue($model->id == $this->bar->id);
    }

    public function test_id_can_be_retrived_with_identifier()
    {
        $model_id = IdentifierModel::getIdByIdentifier('bar');

        $this->assertTrue($model_id == $this->bar->id);
    }

    public function test_identifier_can_be_checked()
    {
        $check_true = IdentifierModel::checkIdentifier(2, 'bar');
        $check_false = IdentifierModel::checkIdentifier(2, 'foo');

        $this->assertTrue($check_true);
        $this->assertFalse($check_false);
    }

    public function test_identifier_are_cached_the_first_time_they_are_retrieved()
    {
        IdentifierModel::resetFetchedIdentifiers();

        $this->assertFalse(IdentifierModel::identifierHasBeenFetched('bar'));

        IdentifierModel::getIdByIdentifier('bar');

        $this->assertTrue(IdentifierModel::identifierHasBeenFetched('bar'));

        IdentifierModel::resetFetchedIdentifiers();

        $this->assertFalse(IdentifierModel::identifierHasBeenFetched('bar'));
    }

    public function test_identifier_can_be_based_on_another_key()
    {
        $this->assertTrue(IdentifierModel::getIdentifierKey() == 'identifier');

        IdentifierModel::$key = 'other_key';

        $this->assertTrue(IdentifierModel::getIdByIdentifier('world') == $this->bar->id);

        $this->assertTrue(IdentifierModel::getIdentifierKey() == 'other_key');
    }
}

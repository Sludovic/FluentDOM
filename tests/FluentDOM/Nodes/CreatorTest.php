<?php
namespace FluentDOM\Nodes {

  use FluentDOM\Appendable;
  use FluentDOM\Document;
  use FluentDOM\Element;
  use FluentDOM\TestCase;
  use FluentDOM\Nodes;

  require_once(__DIR__.'/../TestCase.php');

  class CreatorTest extends TestCase {

    /**
     * @covers \FluentDOM\Nodes\Creator
     */
    public function testClone() {
      $creator = new Creator();
      $clone = clone $creator;
      $this->assertNotSame(
        $this->readAttribute($creator, '_document'),
        $this->readAttribute($clone, '_document')
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreate() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml/>',
        (string)$_('xml')
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testWithNamespace() {
      $_ = new Creator();
      $_->registerNamespace('#default', 'urn:foo');
      $this->assertXmlStringEqualsXmlString(
        '<xml xmlns="urn:foo"/>',
        (string)$_('xml')
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateFetchingDocument() {
      $_ = new Creator();
      $dom = $_('xml', $_('child'))->document;
      $this->assertInstanceOf(Document::class, $dom);
      $this->assertXmlStringEqualsXmlString(
        '<xml><child/></xml>', $dom->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateFetchingDom() {
      $_ = new Creator();
      $dom = $_('xml', $_('child'))->document;
      $this->assertInstanceOf(Document::class, $dom);
      $this->assertXmlStringEqualsXmlString(
        '<xml><child/></xml>', $dom->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateFetchingNode() {
      $_ = new Creator();
      $node = $_('xml', $_('child'))->node;
      $this->assertInstanceOf(Element::class, $node);
      $this->assertXmlStringEqualsXmlString(
        '<xml><child/></xml>', $node->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testElement() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml attr="value"/>',
        $_->element('xml', ['attr' => 'value'])->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testElementWithAttributes() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml attr="value"/>',
        $_->element('xml', ['attr' => 'value'])->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithTextNode() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml>text</xml>',
        $_->element('xml', 'text')->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithSeveralTextNodes() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml>onetwo</xml>',
        $_->element('xml', 'one', 'two')->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithChildNodes() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml><child-one/><child-two/></xml>',
        $_->element('xml', $_('child-one'), $_('child-two'))->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithCdata() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml><![CDATA[ cdata-text ]]></xml>',
        $_->element('xml', $_->cdata(' cdata-text '))->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithComment() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml><!--comment-text--></xml>',
        $_->element('xml', $_->comment('comment-text'))->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithProcessingInstruction() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml><?pi content?></xml>',
        $_->element('xml', $_->pi('pi', 'content'))->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Nodes
     */
    public function testEach() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml><one/><two/></xml>',
        $_->element(
          'xml',
          $_->each(
            [$_('one'), $_('two')]
          )
        )->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Nodes
     */
    public function testEachWithIterator() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml><one/><two/></xml>',
        $_->element(
          'xml',
          $_->each(
            new \ArrayIterator([$_('one'), $_('two')])
          )
        )->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Nodes
     */
    public function testEachWithIteratorAggregate() {
      $_ = new Creator();
      $mock = $this->getMockBuilder(\IteratorAggregate::class)->getMock();
      $mock
        ->expects($this->once())
        ->method('getIterator')
        ->will(
          $this->returnValue(
            new \ArrayIterator([$_('one'), $_('two')])
          )
        );
      $this->assertXmlStringEqualsXmlString(
        '<xml><one/><two/></xml>',
        $_->element(
          'xml',
          $_->each($mock)
        )->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Nodes
     */
    public function testEachWithMapping() {
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml><one/><two/></xml>',
        $_->element(
          'xml',
          $_->each(
            ['one', 'two'],
            function ($name) use ($_) {
              return $_($name);
            }
          )
        )->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Nodes
     */
    public function testEachReturnsIterator() {
      $_ = new Creator();
      $iterator = $_->each(['one', 'two']);
      $this->assertInstanceOf(
        'FluentDOM\Nodes\Creator\Nodes', $iterator
      );
      $this->assertEquals(
        ['one', 'two'], iterator_to_array($iterator)
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithDOMNode() {
      $dom = new \DOMDocument();
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml><child/></xml>',
        $_->element('xml', $dom->createElement('child'))->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithAttributeNode() {
      $dom = new \DOMDocument();
      $attribute = $dom->createAttribute('attr');
      $attribute->value = 'value';
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml attr="value"/>',
        $_->element('xml', $attribute)->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateWithAttributeAppendable() {
      $appendable = $this->getMockBuilder(Appendable::class)->getMock();
      $appendable
        ->expects($this->once())
        ->method('appendTo')
        ->with($this->isInstanceOf(Element::class));
      $dom = new \DOMDocument();
      $attribute = $dom->createAttribute('attr');
      $attribute->value = 'value';
      $_ = new Creator();
      $this->assertXmlStringEqualsXmlString(
        '<xml/>',
        $_->element('xml', $appendable)->saveXml()
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateFetchingUnknownPropertyExpectingNull() {
      $_ = new Creator();
      $this->assertNull(
        $_('foo')->unknown
      );
    }

    /**
     * @covers \FluentDOM\Nodes\Creator\Node
     */
    public function testCreateSetPropertyOnResultExpectingException() {
      $_ = new Creator();
      $this->expectException(\LogicException::class);
      $_('foo')->document = 'bar';
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     */
    public function testCreatorGetFormatOutputAfterSet() {
      $_ = new Creator();
      $_->formatOutput = TRUE;
      $this->assertTrue(isset($_->formatOutput));
      $this->assertTrue($_->formatOutput);
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     */
    public function testCreatorGetUnknownPropertyExpectingNull() {
      $_ = new Creator();
      $this->assertFalse(isset($_->unkown));
      $this->assertNull($_->unkown);
    }

    /**
     * @covers \FluentDOM\Nodes\Creator
     */
    public function testCreatorGetUnknownPropertyAfterSet() {
      $_ = new Creator();
      $_->unkown = TRUE;
      $this->assertTrue($_->unkown);
    }
  }
}
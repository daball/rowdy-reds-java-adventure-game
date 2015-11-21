<?php

namespace commands\tests;
use \commands\TabletCall;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/commands/TabletCall.php';

class TabletCallTest extends \PHPUnit_Framework_TestCase
{
  public function testStripComments()
  {
    $validComments = array(
      "ab" => "a/* multiline \n comment */b",
      "ac" => "a/* multiline comment //with\n single line comment*/c",
      "ad" => "a/**/d",
      "ae" => "a/*//*/e",
      "a\nf" => "a//nothing to see here\nf",
      "a\ng" => "a/////////////////////\ng",
      "a\nh" => "a//* *////////////////\nh",
    );
    $tabletCall = new TabletCall();

    foreach ($validComments as $expected=>$comment)
    {
      $this->assertEquals($expected, $tabletCall->stripComments($comment));
    }
  }

  public function testMethodParameters()
  {
    $validParameters = "String myString, int myInt, bool myBool";
    $tabletCall = new TabletCall();
    $parameters = $tabletCall->getAllMethodParameters($validParameters);

    $this->assertEquals(3, count($parameters));
    foreach ($parameters as $parameter)
    {
      $this->assertEquals(strtolower($parameter->type), strtolower(substr($parameter->name, 2)));
    }
  }

  public function testMethodSignature()
  {
    $validSignatures = "void main ( String myString , int myInt , bool myBool ) { }\n"
                    . "int main2(){}";
    $validCalls = array(
      "main(\"string\", 12, true);",
      "main(myString,myInt,myBool);",
      "main2();"
    );
    $tabletCall = new TabletCall();
    $methods = $tabletCall->getAllMethods($validSignatures);

    $this->assertEquals(2, count($methods));

    $this->assertEquals("main", $methods[0]->name);
    $this->assertEquals("void", $methods[0]->type);
    $this->assertEquals(3, count($methods[0]->parameters));
    $this->assertEquals("String", $methods[0]->parameters[0]->type);
    $this->assertEquals("myString", $methods[0]->parameters[0]->name);
    $this->assertEquals("int", $methods[0]->parameters[1]->type);
    $this->assertEquals("myInt", $methods[0]->parameters[1]->name);
    $this->assertEquals("bool", $methods[0]->parameters[2]->type);
    $this->assertEquals("myBool", $methods[0]->parameters[2]->name);

    $this->assertEquals("main2", $methods[1]->name);
    $this->assertEquals("int", $methods[1]->type);
    $this->assertEquals(0, count($methods[1]->parameters));

    $methodCall = $tabletCall->getMethodCall($validCalls[0]);
    $this->assertEquals("main", $methodCall->name);
    $this->assertEquals(3, count($methodCall->parameters));
    $this->assertEquals("\"string\"", $methodCall->parameters[0]->parameter);
    $this->assertEquals("12", $methodCall->parameters[1]->parameter);
    $this->assertEquals("true", $methodCall->parameters[2]->parameter);

    $methodCall = $tabletCall->getMethodCall($validCalls[1]);
    $this->assertEquals("main", $methodCall->name);
    $this->assertEquals(3, count($methodCall->parameters));
    $this->assertEquals("myString", $methodCall->parameters[0]->parameter);
    $this->assertEquals("myInt", $methodCall->parameters[1]->parameter);
    $this->assertEquals("myBool", $methodCall->parameters[2]->parameter);

    $methodCall = $tabletCall->getMethodCall($validCalls[2]);
    $this->assertEquals("main2", $methodCall->name);
    $this->assertEquals(0, count($methodCall->parameters));

    $this->assertTrue($tabletCall->verifyCallMatchesDefinition($methods[0], $tabletCall->getMethodCall($validCalls[0])));
    $this->assertTrue($tabletCall->verifyCallMatchesDefinition($methods[0], $tabletCall->getMethodCall($validCalls[1])));
    $this->assertTrue($tabletCall->verifyCallMatchesDefinition($methods[1], $tabletCall->getMethodCall($validCalls[2])));

    $this->assertTrue($tabletCall->validateCommand($validCalls[0], $validSignatures));
    $this->assertTrue($tabletCall->validateCommand($validCalls[1], $validSignatures));
    $this->assertTrue($tabletCall->validateCommand($validCalls[2], $validSignatures));

    echo $tabletCall->executeCommand($validCalls[0], $validSignatures);
    $this->assertTrue(!!$tabletCall->executeCommand($validCalls[0], $validSignatures));
    $this->assertTrue(!!$tabletCall->executeCommand($validCalls[1], $validSignatures));
    $this->assertTrue(!!$tabletCall->executeCommand($validCalls[2], $validSignatures));
  }
}

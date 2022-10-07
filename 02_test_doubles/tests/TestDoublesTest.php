<?php

use PHPUnit\Framework\TestCase;

/**
 * TestDoublesTest
 */
class TestDoublesTest extends TestCase
{
    private $mock;

    // runs before each test
    protected function setUp(): void
    {
        // mock service instance
        $this->mock = $this->createMock(\App\ExampleService::class);
    }
    
    /** @test */
    public function test_mock_service()
    {
        // mock return value of doSomething()
        $this->mock->expects($this->exactly(1)) // number of invocation(s)
             ->method('doSomething')
             ->with('bar') // expected args
             ->willReturn('foo');


        // example command instance with mock service instance
        $exampleCommand = new \App\ExampleCommand($this->mock);

        // assertions
        $this->assertSame('foo', $exampleCommand->execute('bar'));
    }

    /** @test */ 
    public function test_return_types() 
    {
        $this->assertNull($this->mock->doSomething('bar'));
    }

    /** @test */
    public function test_consecutive_returns()
    {
        $this->mock->method('doSomething')
                   ->willReturnOnConsecutiveCalls(1,2);
                //    ->will($this->onConsecutiveCalls(1,2));

        foreach([1, 2] as $value) {
            $this->assertSame($value, $this->mock->doSomething('bar'));
        }
    }

    /** 
     * @test
     * @expectedException  exception
     */
    public function test_exceptions_thrown()
    {
        $this->mock->method('doSomething')
                //    ->will($this->throwException(new RuntimeException()));
                    ->willThrowException(new RuntimeException());

        $this->expectException(RuntimeException::class);

        $this->mock->doSomething('bar');
    }

    /** @test */
    public function test_callback_returns()
    {
        $this->mock->method('doSomething')
                   ->willReturnCallback(function ($arg) {
                        if ($arg % 2 == 0) {
                            return $arg;
                        }
                        throw new InvalidArgumentException();
                   });

        $this->assertSame(10, $this->mock->doSomething(10));

        $this->expectException(InvalidArgumentException::class);

        $this->mock->doSomething(9);
    }

    /** @test */
    public function test_with_equal_to_argument_passed()
    {
        $this->mock->expects($this->exactly(1))
                   ->method('doSomething')
                   ->with($this->equalTo('bar'));

        $this->mock->doSomething('bar');
    }

    /** @test */
    public function test_multiple_args()
    {
        $this->mock->expects($this->once())
                   ->method('doSomething')
                   ->with(
                        $this->stringContains('foo'),
                        $this->greaterThanOrEqual(100),
                        $this->anything()
                   );

        $this->mock->doSomething('foobar', 105, null);
    }

    /** @test */
    public function test_consecutive_args()
    {
        $this->mock->expects($this->exactly(2))
                   ->method('doSomething')
                   ->withConsecutive(
                        [$this->stringContains('foo'), $this->greaterThanOrEqual(100)],
                        [$this->isNull(), $this->greaterThanOrEqual(10)]
                   );

        $this->mock->doSomething('foobar', 105);
        $this->mock->doSomething(null, 14);
    }

    /** @test */
    public function test_callback_arguments()
    {
        $this->mock->expects($this->once())
                   ->method('doSomething')
                   ->with($this->callback(function($object) {
                        $this->assertInstanceOf(\App\ExampleDependency::class, $object);
                        return $object->exampleMethod() === 'Example string';
                   }));

        $this->mock->doSomething(new \App\ExampleDependency());
    }

    /** @test */
    public function test_identical_object_instances()
    {
        $dependency = new \App\ExampleDependency();

        $this->mock->expects($this->once())
                   ->method('doSomething')
                   ->with($this->identicalTo($dependency));

        $this->mock->doSomething($dependency);
    }
    
    /** @test */
    public function test_mock_builder()
    {
        $mock = $this->getMockBuilder(\App\ExampleService::class)
                     ->setConstructorArgs([100, 200])
                     ->getMock();

        $mock->method('doSomething')->willReturn('foo');

        $this->assertSame('foo', $mock->doSomething('bar'));
    }

    /** @test */
    public function test_only_methods()
    {
        $mock = $this->getMockBuilder(\App\ExampleService::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods(['doSomething'])
                     ->getMock();

        $mock->method('doSomething')->willReturn('foo');

        $this->assertSame('foo', $mock->nonMockedMethod('bar'));
    }

    /** @test */
    public function test_add_methods()
    {
        $mock = $this->getMockBuilder(\App\ExampleService::class)
                     ->disableOriginalConstructor()
                     ->addMethods(['nonExistentMethod'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('nonExistentMethod')
             ->with($this->isInstanceOf(\App\ExampleDependency::class))
             ->willReturn('foo');

        $this->assertSame('foo', $mock->nonExistentMethod(new \App\ExampleDependency()));
    }
    
    
}

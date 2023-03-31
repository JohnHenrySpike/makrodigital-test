<?

use Tests\TestCase;

final class IndexContollerTest extends TestCase{
    public function testIndexPageResponce(){
        $r = $this->get("/");
        $this->assertEquals("index page", $r->getContent());
    }
}

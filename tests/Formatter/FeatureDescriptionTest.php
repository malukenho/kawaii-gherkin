<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace KawaiiGherkinTest\Formatter;

use Behat\Gherkin\Node\FeatureNode;
use KawaiiGherkin\Formatter\FeatureDescription;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FeatureDescription::class)]
final class FeatureDescriptionTest extends TestCase
{
    private FeatureDescription $formatter;

    public function setUp(): void
    {
        $this->formatter = new FeatureDescription();
    }

    #[Test]
    public function canFormatFeatureDescription(): void
    {
        $expected =<<<EOS
Feature: How to be a kawai person
    In order to be a kawai person
    As a non-kawai person
    I have to turn me a PHP programmer
EOS;

        $shortDescription = 'How to be a kawai person';
        $longDescription  = [
            '           In order to be a kawai person',
            'As a non-kawai person                  ',
            '                   I have to turn me a PHP programmer',
        ];

        $feature = new FeatureNode(
            $shortDescription,
            implode("\n", $longDescription),
            [],
            null,
            [],
            'Feature',
            'en',
            '/tmp.feature',
            12
        );

        self::assertSame($expected, $this->formatter->format($feature));
    }
}

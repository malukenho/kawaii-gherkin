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

namespace KawaiiGherkin\Formatter;

use Behat\Gherkin\Node\OutlineNode;

/**
 * @author Jefersson Nathan  <malukenho@phpse.net>
 * @license MIT
 */
final class Example extends AbstractFormatter
{
    /**
     * @param OutlineNode $scenario
     *
     * @return string
     */
    public function format(OutlineNode $scenario)
    {
        if (! $scenario->hasExamples()) {
            return;
        }

        // TODO: refactor this part
        return implode(
            array_merge(
                [
                    $this->indent(self::INDENTATION * 2) . rtrim($scenario->getExampleTable()->getKeyword()) . ':' . PHP_EOL,
                ],
                array_map(
                    function ($arguments) {
                        return $this->indent(self::INDENTATION * 2 + 2) . trim($arguments) . PHP_EOL;
                    },
                    explode(PHP_EOL, $scenario->getExampleTable()->getTableAsString())
                )
            )
        );
    }
}

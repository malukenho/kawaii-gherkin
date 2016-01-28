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

use Behat\Gherkin\Node\BackgroundNode;
use Behat\Gherkin\Node\TableNode;

/**
 * @author Jefersson Nathan  <malukenho@phpse.net>
 * @license MIT
 */
final class Background
{
    public function format(BackgroundNode $background, $indentation = 4)
    {
        $indentationAlign = $indentation * 2;

        $shortDesc   = $this->getBackgroundShortDescription($background) . PHP_EOL;
        $longDesc    = '';

        foreach ($background->getSteps() as $step) {
            $indentSpaces =  $indentationAlign - strlen(trim($step->getKeyword())) + 1;
            $longDesc .= str_repeat(' ', $indentSpaces + $indentation) .
                trim($step->getKeyword()) . ' ' . trim($step->getText()) . PHP_EOL;

            if ($step->hasArguments()) {
                /* @var $argument TableNode */
                foreach ($step->getArguments() as $argument) {
                    if ($argument->getNodeType() === 'Table') {
                        $longDesc .= implode('', array_map(
                                function ($arguments) use ($indentationAlign) {
                                    return str_repeat(' ', $indentationAlign + 4) . trim($arguments) . PHP_EOL;
                                },
                                explode("\n", $argument->getTableAsString())
                            )
                        );
                    }
                }
            }
        }

        return str_repeat(' ', $indentation) . $shortDesc . rtrim($longDesc);
    }

    /**
     * @param BackgroundNode $background
     *
     * @return string
     */
    private function getBackgroundShortDescription(BackgroundNode $background)
    {
        return trim(sprintf('%s: %s', trim($background->getKeyword()), trim($background->getTitle())));
    }
}

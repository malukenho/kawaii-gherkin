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

/**
 * @author  Jefersson Nathan  <malukenho@phpse.net>
 * @license MIT
 */
final class Background extends AbstractFormatter
{
    private $align;

    /**
     * @param string $align
     */
    public function __construct($align = Step::ALIGN_TO_RIGHT)
    {
        $this->align = $align;
    }

    /**
     * @param BackgroundNode $background
     *
     * @return string
     */
    public function format(BackgroundNode $background)
    {
        $shortDesc = $this->getBackgroundShortDescription($background) . "\n";

        $step  = new Step($this->align);
        $steps = $step->format($background->getSteps());

        return $this->indent() . $shortDesc . $steps;
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

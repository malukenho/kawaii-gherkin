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

/**
 * @author Jefersson Nathan <malukenho@phpse.net>
 */
final class ComposedFormatter
{
    /**
     * @var AbstractFormatter
     */
    private $featureDescription;

    /**
     * @var AbstractFormatter
     */
    private $background;

    /**
     * @var AbstractFormatter
     */
    private $scenario;

    /**
     * @var AbstractFormatter
     */
    private $tags;

    /**
     * @param AbstractFormatter $featureDescription
     * @param AbstractFormatter $background
     * @param AbstractFormatter $scenario
     * @param AbstractFormatter $tags
     */
    public function __construct(
        AbstractFormatter $featureDescription,
        AbstractFormatter $background,
        AbstractFormatter $scenario,
        AbstractFormatter $tags
    ) {
        $this->featureDescription = $featureDescription;
        $this->background         = $background;
        $this->scenario           = $scenario;
        $this->tags               = $tags;
    }

    public function __invoke($feature)
    {
        $formatted  = $feature->getLanguage() && $feature->getLanguage() !== 'en' ? '# language: ' . trim($feature->getLanguage()) . "\n" : '';
        $formatted .= $feature->hasTags() ? $this->tags->format($feature->getTags()) . "\n" : '';
        $formatted .= $this->featureDescription->format($feature) . "\n\n";
        $formatted .= $feature->hasBackground() ? $this->background->format($feature->getBackground()) . "\n" : '';
        $formatted .= $feature->hasScenarios() ? $this->scenario->format($feature->getScenarios()) : '';

        return $formatted;
    }
}

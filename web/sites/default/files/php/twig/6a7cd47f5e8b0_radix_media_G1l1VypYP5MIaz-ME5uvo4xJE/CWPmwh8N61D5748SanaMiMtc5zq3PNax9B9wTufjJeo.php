<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Sandbox\SecurityNotAllowedTestError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* radix:media */
class __TwigTemplate_46950f426fe5e78e15a69ff1ef2302e7 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'media_content' => [$this, 'block_media_content'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--media"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:media"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:media"));
        // line 16
        $context["classes"] = Twig\Extension\CoreExtension::merge(["media", ("media--type-" . \Drupal\Component\Utility\Html::getClass(CoreExtension::getAttribute($this->env, $this->source,         // line 18
($context["media"] ?? null), "bundle", [], "method", false, false, true, 18))), (( !(($tmp = CoreExtension::getAttribute($this->env, $this->source,         // line 19
($context["media"] ?? null), "isPublished", [], "method", false, false, true, 19)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("media--unpublished") : ("")), (((($tmp =         // line 20
($context["view_mode"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("media--view-mode-" . \Drupal\Component\Utility\Html::getClass(($context["view_mode"] ?? null)))) : (""))], (((($tmp =         // line 21
($context["media_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["media_utility_classes"]) : ([])));
        // line 23
        yield "
";
        // line 24
        $context["media_attributes"] = (((($tmp = ($context["attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 25
        yield "
<div ";
        // line 26
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["media_attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 26), "html", null, true);
        yield ">
  ";
        // line 27
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["title_suffix"] ?? null), "contextual_links", [], "any", false, false, true, 27), "html", null, true);
        yield "
  ";
        // line 28
        yield from $this->unwrap()->yieldBlock('media_content', $context, $blocks);
        // line 33
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["media", "view_mode", "media_utility_classes", "attributes", "title_suffix", "content"]);        yield from [];
    }

    // line 28
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_media_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 29
        yield "    ";
        if ((($tmp = ($context["content"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 30
            yield "      ";
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["content"] ?? null), "html", null, true);
            yield "
    ";
        }
        // line 32
        yield "  ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:media";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  96 => 32,  90 => 30,  87 => 29,  80 => 28,  73 => 33,  71 => 28,  67 => 27,  63 => 26,  60 => 25,  58 => 24,  55 => 23,  53 => 21,  52 => 20,  51 => 19,  50 => 18,  49 => 16,  45 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:media", "themes/contrib/radix/components/media/media.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 16, "block" => 28, "if" => 29];
        static $filters = ["merge" => 21, "clean_class" => 18, "escape" => 26];
        static $functions = ["create_attribute" => 24];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "block", 2 => "if"],
                [0 => "merge", 1 => "clean_class", 2 => "escape"],
                [0 => "create_attribute"],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            } elseif ($e instanceof SecurityNotAllowedTestError && isset($tests[$e->getTestName()])) {
                $e->setTemplateLine($tests[$e->getTestName()]);
            }

            throw $e;
        }

    }
}

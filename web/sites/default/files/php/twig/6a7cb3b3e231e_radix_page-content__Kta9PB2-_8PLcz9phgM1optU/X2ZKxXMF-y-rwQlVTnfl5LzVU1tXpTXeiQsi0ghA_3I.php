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

/* radix:page-content */
class __TwigTemplate_11782e74490aae388dd0984f89ed9b3f extends Template
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
            'page_header' => [$this, 'block_page_header'],
            'page_inner_content' => [$this, 'block_page_inner_content'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--page-content"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:page-content"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:page-content"));
        // line 18
        $context["page_header_container_attributes"] = (((($tmp = ($context["page_header_container_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["page_header_container_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 19
        $context["page_content_container_attributes"] = (((($tmp = ($context["page_content_container_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["page_content_container_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 20
        yield "
";
        // line 22
        $context["page_main_classes"] = Twig\Extension\CoreExtension::merge([], (((($tmp = ($context["page_main_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["page_main_utility_classes"]) : ([])));
        // line 24
        yield "
";
        // line 25
        $context["header_container_class"] = "";
        // line 26
        if ((null === ($context["page_header_container_type"] ?? null))) {
            // line 27
            yield "  ";
            $context["header_container_class"] = "container";
        } elseif ( !Twig\Extension\CoreExtension::testEmpty(        // line 28
($context["page_header_container_type"] ?? null))) {
            // line 29
            yield "  ";
            $context["header_container_class"] = ("container-" . ($context["page_header_container_type"] ?? null));
        }
        // line 32
        $context["page_header_container_classes"] = Twig\Extension\CoreExtension::merge([        // line 33
($context["header_container_class"] ?? null)], (((($tmp =         // line 34
($context["page_header_container_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["page_header_container_utility_classes"]) : ([])));
        // line 36
        yield "
";
        // line 37
        $context["content_container_class"] = "";
        // line 38
        if ((null === ($context["page_content_container_type"] ?? null))) {
            // line 39
            yield "  ";
            $context["content_container_class"] = "container";
        } elseif ( !Twig\Extension\CoreExtension::testEmpty(        // line 40
($context["page_content_container_type"] ?? null))) {
            // line 41
            yield "  ";
            $context["content_container_class"] = ("container-" . ($context["page_content_container_type"] ?? null));
        }
        // line 44
        $context["page_content_container_classes"] = Twig\Extension\CoreExtension::merge([        // line 45
($context["content_container_class"] ?? null)], (((($tmp =         // line 46
($context["page_content_container_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["page_content_container_utility_classes"]) : ([])));
        // line 48
        yield "
<main";
        // line 49
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [($context["page_main_classes"] ?? null)], "method", false, false, true, 49), "html", null, true);
        yield ">
\t";
        // line 50
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 50)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 51
            yield "\t\t<header class=\"page__header\">
\t\t\t<div ";
            // line 52
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["page_header_container_attributes"] ?? null), "addClass", [($context["page_header_container_classes"] ?? null)], "method", false, false, true, 52), "html", null, true);
            yield ">
\t\t\t\t";
            // line 53
            yield from $this->unwrap()->yieldBlock('page_header', $context, $blocks);
            // line 56
            yield "\t\t\t</div>
\t\t</header>
\t";
        }
        // line 59
        yield "
\t";
        // line 60
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 60)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 61
            yield "    <div class=\"page__content\" id=\"main-content\">
      <div ";
            // line 62
            yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["page_content_container_attributes"] ?? null), "addClass", [($context["page_content_container_classes"] ?? null)], "method", false, false, true, 62), "html", null, true);
            yield ">
        ";
            // line 63
            yield from $this->unwrap()->yieldBlock('page_inner_content', $context, $blocks);
            // line 66
            yield "      </div>
    </div>
  ";
        }
        // line 69
        yield "</main>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["page_main_utility_classes", "page_header_container_type", "page_header_container_utility_classes", "page_content_container_type", "page_content_container_utility_classes", "content_attributes", "page"]);        yield from [];
    }

    // line 53
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_header(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 54
        yield "\t\t\t\t\t";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 54), "html", null, true);
        yield "
\t\t\t\t";
        yield from [];
    }

    // line 63
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_inner_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 64
        yield "          ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 64), "html", null, true);
        yield "
        ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:page-content";
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
        return array (  170 => 64,  163 => 63,  155 => 54,  148 => 53,  141 => 69,  136 => 66,  134 => 63,  130 => 62,  127 => 61,  125 => 60,  122 => 59,  117 => 56,  115 => 53,  111 => 52,  108 => 51,  106 => 50,  102 => 49,  99 => 48,  97 => 46,  96 => 45,  95 => 44,  91 => 41,  89 => 40,  86 => 39,  84 => 38,  82 => 37,  79 => 36,  77 => 34,  76 => 33,  75 => 32,  71 => 29,  69 => 28,  66 => 27,  64 => 26,  62 => 25,  59 => 24,  57 => 22,  54 => 20,  52 => 19,  50 => 18,  46 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:page-content", "themes/contrib/radix/components/page-content/page-content.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 18, "if" => 26, "block" => 53];
        static $filters = ["merge" => 22, "escape" => 49];
        static $functions = ["create_attribute" => 18];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if", 2 => "block"],
                [0 => "merge", 1 => "escape"],
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

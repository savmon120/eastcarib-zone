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

/* radix:page */
class __TwigTemplate_dcb7c342d1af9f22454093ea7b9f3db2 extends Template
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
            'page_navigation' => [$this, 'block_page_navigation'],
            'page_content' => [$this, 'block_page_content'],
            'page_footer' => [$this, 'block_page_footer'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--page"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:page"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:page"));
        // line 7
        $context["page_attributes"] = (((($tmp = ($context["attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 8
        $context["page_classes"] = Twig\Extension\CoreExtension::merge(["page"], (((($tmp =         // line 11
($context["page_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["page_utility_classes"]) : ([])));
        // line 13
        yield "
<div ";
        // line 14
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["page_attributes"] ?? null), "addClass", [($context["page_classes"] ?? null)], "method", false, false, true, 14), "html", null, true);
        yield ">
\t";
        // line 15
        yield from $this->unwrap()->yieldBlock('page_navigation', $context, $blocks);
        // line 18
        yield "
  ";
        // line 19
        yield from $this->unwrap()->yieldBlock('page_content', $context, $blocks);
        // line 28
        yield "
\t";
        // line 29
        yield from $this->unwrap()->yieldBlock('page_footer', $context, $blocks);
        // line 32
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "page_utility_classes"]);        yield from [];
    }

    // line 15
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_navigation(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 16
        yield "\t\t";
        yield from $this->load("radix:page-navigation", 16)->unwrap()->yield($context);
        // line 17
        yield "\t";
        yield from [];
    }

    // line 19
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 20
        yield "    ";
        $context["page_main_utility_classes"] = (((($tmp = ($context["page_main_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["page_main_utility_classes"]) : (["py-5"]));
        // line 21
        yield "    ";
        $context["page_header_container_utility_classes"] = (((($tmp = ($context["page_header_container_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["page_header_container_utility_classes"]) : (["mb-3"]));
        // line 22
        yield "
    ";
        // line 23
        yield from $this->load("radix:page-content", 23)->unwrap()->yield(CoreExtension::merge($context, ["page_main_utility_classes" =>         // line 24
($context["page_main_utility_classes"] ?? null), "page_header_container_utility_classes" =>         // line 25
($context["page_header_container_utility_classes"] ?? null)]));
        // line 27
        yield "  ";
        yield from [];
    }

    // line 29
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_footer(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 30
        yield "\t\t";
        yield from $this->load("radix:page-footer", 30)->unwrap()->yield($context);
        // line 31
        yield "\t";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:page";
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
        return array (  132 => 31,  129 => 30,  122 => 29,  117 => 27,  115 => 25,  114 => 24,  113 => 23,  110 => 22,  107 => 21,  104 => 20,  97 => 19,  92 => 17,  89 => 16,  82 => 15,  75 => 32,  73 => 29,  70 => 28,  68 => 19,  65 => 18,  63 => 15,  59 => 14,  56 => 13,  54 => 11,  53 => 8,  51 => 7,  47 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:page", "themes/contrib/radix/components/page/page.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 7, "block" => 15, "include" => 16];
        static $filters = ["merge" => 11, "escape" => 14];
        static $functions = ["create_attribute" => 7];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "block", 2 => "include"],
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

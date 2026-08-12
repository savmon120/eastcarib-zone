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

/* themes/custom/east_caribbean/templates/field/time.html.twig */
class __TwigTemplate_e1c278c049fabc2396bb5cb34e86a676 extends Template
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
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 23
        $context["time_classes"] = Twig\Extension\CoreExtension::merge(["time"], (((($tmp =         // line 25
($context["time_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["time_utility_classes"]) : ([])));
        // line 27
        $context["time_attributes"] = (((($tmp = ($context["attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 28
        yield "
<time ";
        // line 29
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["time_attributes"] ?? null), "addClass", [($context["time_class"] ?? null)], "method", false, false, true, 29), "html", null, true);
        yield ">";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["text"] ?? null), "html", null, true);
        yield "</time>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["time_utility_classes", "attributes", "time_class", "text"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/custom/east_caribbean/templates/field/time.html.twig";
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
        return array (  52 => 29,  49 => 28,  47 => 27,  45 => 25,  44 => 23,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/custom/east_caribbean/templates/field/time.html.twig", "/var/www/html/web/themes/custom/east_caribbean/templates/field/time.html.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 23];
        static $filters = ["merge" => 25, "escape" => 29];
        static $functions = ["create_attribute" => 27];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set"],
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

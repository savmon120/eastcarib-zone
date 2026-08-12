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

/* radix:navbar */
class __TwigTemplate_829dc3a006d73895e888e2620bce55ba extends Template
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
            'branding' => [$this, 'block_branding'],
            'navbar_toggler' => [$this, 'block_navbar_toggler'],
            'left' => [$this, 'block_left'],
            'right' => [$this, 'block_right'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--navbar"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:navbar"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:navbar"));
        // line 22
        $context["nav_attributes"] = (((($tmp = ($context["nav_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["nav_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 23
        $context["navbar_container_attributes"] = (((($tmp = ($context["navbar_container_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["navbar_container_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()));
        // line 24
        yield "
";
        // line 25
        $context["placement"] = (((array_key_exists("placement", $context) &&  !(null === $context["placement"]))) ? ($context["placement"]) : (""));
        // line 26
        $context["navbar_expand"] = (((array_key_exists("navbar_expand", $context) &&  !(null === $context["navbar_expand"]))) ? ($context["navbar_expand"]) : ("lg"));
        // line 27
        $context["navbar_theme"] = (((array_key_exists("navbar_theme", $context) &&  !(null === $context["navbar_theme"]))) ? ($context["navbar_theme"]) : (null));
        // line 28
        $context["navbar_id"] = ((array_key_exists("navbar_id", $context)) ? (Twig\Extension\CoreExtension::default(($context["navbar_id"] ?? null), ("navbar-collapse-" . Twig\Extension\CoreExtension::random($this->env->getCharset(), 1000)))) : (("navbar-collapse-" . Twig\Extension\CoreExtension::random($this->env->getCharset(), 1000))));
        // line 29
        yield "
";
        // line 31
        $context["navbar_container_classes"] = Twig\Extension\CoreExtension::merge([(((null ===         // line 32
($context["navbar_container_type"] ?? null))) ? ("container") : ("")), (((($tmp =         // line 33
($context["navbar_container_type"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("container" . (((($tmp = ($context["navbar_container_type"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("-" . ($context["navbar_container_type"] ?? null))) : ("")))) : (""))], (((($tmp =         // line 34
($context["navbar_container_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["navbar_container_utility_classes"]) : ([])));
        // line 36
        yield "
";
        // line 38
        $context["nav_classes"] = Twig\Extension\CoreExtension::merge(["navbar", (((($tmp =         // line 40
($context["navbar_expand"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (("navbar-expand-" . ($context["navbar_expand"] ?? null))) : ("")),         // line 41
($context["placement"] ?? null)], (((($tmp =         // line 42
($context["navbar_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["navbar_utility_classes"]) : ([])));
        // line 44
        yield "
";
        // line 45
        if ((($tmp = ($context["navbar_theme"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 46
            yield "  ";
            $context["nav_attributes"] = CoreExtension::getAttribute($this->env, $this->source, ($context["nav_attributes"] ?? null), "setAttribute", ["data-bs-theme", ($context["navbar_theme"] ?? null)], "method", false, false, true, 46);
        }
        // line 48
        yield "
<nav ";
        // line 49
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["nav_attributes"] ?? null), "addClass", [($context["nav_classes"] ?? null)], "method", false, false, true, 49), "html", null, true);
        yield ">
  <div ";
        // line 50
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["navbar_container_attributes"] ?? null), "addClass", [($context["navbar_container_classes"] ?? null)], "method", false, false, true, 50), "html", null, true);
        yield ">
    ";
        // line 51
        yield from $this->unwrap()->yieldBlock('branding', $context, $blocks);
        // line 54
        yield "
    ";
        // line 55
        yield from $this->unwrap()->yieldBlock('navbar_toggler', $context, $blocks);
        // line 60
        yield "
    <div id=\"";
        // line 61
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["navbar_id"] ?? null), "html", null, true);
        yield "\" class=\"collapse navbar-collapse\">
      ";
        // line 62
        yield from $this->unwrap()->yieldBlock('left', $context, $blocks);
        // line 65
        yield "
      ";
        // line 66
        yield from $this->unwrap()->yieldBlock('right', $context, $blocks);
        // line 69
        yield "    </div>
  </div>
</nav>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["navbar_container_type", "navbar_container_utility_classes", "navbar_utility_classes", "branding", "left", "right"]);        yield from [];
    }

    // line 51
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_branding(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 52
        yield "      ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["branding"] ?? null), "html", null, true);
        yield "
    ";
        yield from [];
    }

    // line 55
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_navbar_toggler(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 56
        yield "      <button class=\"navbar-toggler collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["navbar_id"] ?? null), "html", null, true);
        yield "\" aria-controls=\"";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["navbar_id"] ?? null), "html", null, true);
        yield "\" aria-expanded=\"false\" aria-label=\"";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Toggle navigation"));
        yield "\">
        <span class=\"navbar-toggler-icon\"></span>
      </button>
    ";
        yield from [];
    }

    // line 62
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_left(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 63
        yield "        ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["left"] ?? null), "html", null, true);
        yield "
      ";
        yield from [];
    }

    // line 66
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_right(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 67
        yield "        ";
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["right"] ?? null), "html", null, true);
        yield "
      ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:navbar";
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
        return array (  191 => 67,  184 => 66,  176 => 63,  169 => 62,  155 => 56,  148 => 55,  140 => 52,  133 => 51,  124 => 69,  122 => 66,  119 => 65,  117 => 62,  113 => 61,  110 => 60,  108 => 55,  105 => 54,  103 => 51,  99 => 50,  95 => 49,  92 => 48,  88 => 46,  86 => 45,  83 => 44,  81 => 42,  80 => 41,  79 => 40,  78 => 38,  75 => 36,  73 => 34,  72 => 33,  71 => 32,  70 => 31,  67 => 29,  65 => 28,  63 => 27,  61 => 26,  59 => 25,  56 => 24,  54 => 23,  52 => 22,  48 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:navbar", "themes/contrib/radix/components/navbar/navbar.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 22, "if" => 45, "block" => 51];
        static $filters = ["default" => 28, "merge" => 34, "escape" => 49, "t" => 56];
        static $functions = ["create_attribute" => 22, "random" => 28];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if", 2 => "block"],
                [0 => "default", 1 => "merge", 2 => "escape", 3 => "t"],
                [0 => "create_attribute", 1 => "random"],
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

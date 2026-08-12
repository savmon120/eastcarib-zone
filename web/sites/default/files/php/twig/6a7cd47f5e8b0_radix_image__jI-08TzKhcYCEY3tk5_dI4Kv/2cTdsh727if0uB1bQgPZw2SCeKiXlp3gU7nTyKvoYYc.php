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

/* radix:image */
class __TwigTemplate_426aac159e8052d64928345e46cfb9c7 extends Template
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
        // line 1
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/components.radix--image"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->addAdditionalContext($context, "radix:image"));
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\ComponentsTwigExtension']->validateProps($context, "radix:image"));
        // line 23
        $context["image_attributes"] = (((($tmp = ($context["attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["attributes"]) : ((((($tmp = ($context["image_attributes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["image_attributes"]) : ($this->extensions['Drupal\Core\Template\TwigExtension']->createAttribute()))));
        // line 24
        yield "
";
        // line 25
        if ((($tmp = ($context["src"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 26
            yield "  ";
            $context["image_attributes"] = CoreExtension::getAttribute($this->env, $this->source, ($context["image_attributes"] ?? null), "setAttribute", ["src", ($context["src"] ?? null)], "method", false, false, true, 26);
            // line 27
            yield "
  ";
            // line 28
            $context["image_attributes"] = CoreExtension::getAttribute($this->env, $this->source, ($context["image_attributes"] ?? null), "setAttribute", ["alt", ((array_key_exists("alt", $context)) ? (Twig\Extension\CoreExtension::default(($context["alt"] ?? null), "")) : (""))], "method", false, false, true, 28);
            // line 29
            yield "
  ";
            // line 30
            if ( !Twig\Extension\CoreExtension::testEmpty(($context["title"] ?? null))) {
                // line 31
                yield "    ";
                $context["image_attributes"] = CoreExtension::getAttribute($this->env, $this->source, ($context["image_attributes"] ?? null), "setAttribute", ["title", ($context["title"] ?? null)], "method", false, false, true, 31);
                // line 32
                yield "  ";
            }
            // line 33
            yield "
  ";
            // line 34
            if ( !Twig\Extension\CoreExtension::testEmpty(($context["width"] ?? null))) {
                // line 35
                yield "    ";
                $context["image_attributes"] = CoreExtension::getAttribute($this->env, $this->source, ($context["image_attributes"] ?? null), "setAttribute", ["width", ($context["width"] ?? null)], "method", false, false, true, 35);
                // line 36
                yield "  ";
            }
            // line 37
            yield "
  ";
            // line 38
            if ( !Twig\Extension\CoreExtension::testEmpty(($context["height"] ?? null))) {
                // line 39
                yield "    ";
                $context["image_attributes"] = CoreExtension::getAttribute($this->env, $this->source, ($context["image_attributes"] ?? null), "setAttribute", ["height", ($context["height"] ?? null)], "method", false, false, true, 39);
                // line 40
                yield "  ";
            }
            // line 41
            yield "
  ";
            // line 42
            $context["image_attributes"] = CoreExtension::getAttribute($this->env, $this->source, ($context["image_attributes"] ?? null), "setAttribute", ["loading", ((array_key_exists("loading", $context)) ? (Twig\Extension\CoreExtension::default(($context["loading"] ?? null), "auto")) : ("auto"))], "method", false, false, true, 42);
        }
        // line 44
        yield "
";
        // line 46
        $context["align_classes"] = ["start" => ["float-start"], "center" => ["mx-auto", "d-block"], "end" => ["float-end"]];
        // line 52
        yield "
";
        // line 53
        $context["image_classes"] = Twig\Extension\CoreExtension::merge([(((($tmp =         // line 54
($context["align"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (Twig\Extension\CoreExtension::join((($_v0 = ($context["align_classes"] ?? null)) && is_array($_v0) || $_v0 instanceof ArrayAccess && in_array($_v0::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v0[(($_v1 = ($context["align"] ?? null)) instanceof \Stringable ? (string) $_v1 : $_v1)] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["align_classes"] ?? null), ($context["align"] ?? null), [], "array", false, false, true, 54)), " ")) : ("")), (((($tmp =         // line 55
($context["responsive"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("img-fluid") : ("")), (((($tmp =         // line 56
($context["thumbnails"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("img-thumbnail") : ("")), (((($tmp =         // line 57
($context["rounded"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("rounded") : (""))], (((($tmp =         // line 58
($context["image_utility_classes"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($context["image_utility_classes"]) : ([])));
        // line 60
        yield "
<img";
        // line 61
        yield (string) $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["image_attributes"] ?? null), "addClass", [($context["image_classes"] ?? null)], "method", false, false, true, 61), "html", null, true);
        yield ">
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "src", "alt", "title", "width", "height", "loading", "align", "responsive", "thumbnails", "rounded", "image_utility_classes"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "radix:image";
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
        return array (  120 => 61,  117 => 60,  115 => 58,  114 => 57,  113 => 56,  112 => 55,  111 => 54,  110 => 53,  107 => 52,  105 => 46,  102 => 44,  99 => 42,  96 => 41,  93 => 40,  90 => 39,  88 => 38,  85 => 37,  82 => 36,  79 => 35,  77 => 34,  74 => 33,  71 => 32,  68 => 31,  66 => 30,  63 => 29,  61 => 28,  58 => 27,  55 => 26,  53 => 25,  50 => 24,  48 => 23,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "radix:image", "themes/contrib/radix/components/image/image.twig");
    }
    
    public function ensureSecurityChecked(): void
    {
        if ($this->sandbox->isSandboxed($this->source)) {
            $this->checkSecurity();
        }
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 23, "if" => 25];
        static $filters = ["default" => 28, "merge" => 58, "join" => 54, "escape" => 61];
        static $functions = ["create_attribute" => 23];
        static $tests = [];

        try {
            $this->sandbox->checkSecurity(
                [0 => "set", 1 => "if"],
                [0 => "default", 1 => "merge", 2 => "join", 3 => "escape"],
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

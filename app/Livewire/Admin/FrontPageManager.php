<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class FrontPageManager extends Component
{
    use WithFileUploads;

    // Hero
    public string $heroTitle;
    public string $heroSubtitle;
    public $heroPhoto;
    public ?string $heroPhotoPath = null;
    public $heroPhoto2;
    public ?string $heroPhotoPath2 = null;
    public $heroPhoto3;
    public ?string $heroPhotoPath3 = null;
    public string $heroBtnText;
    public string $heroBtnUrl;

    // Header
    public string $headerMinistry;
    public string $headerDeptEn;
    public string $headerDeptUr;
    public string $headerDeptHi;
    public $headerEmblem;
    public ?string $headerEmblemPath = null;
    public $headerFlag;
    public ?string $headerFlagPath = null;

    // Notice
    public string $noticeLabel;

    // Services
    public string $feature1Title;
    public string $feature1Text;
    public string $feature2Title;
    public string $feature2Text;
    public string $feature3Title;
    public string $feature3Text;
    public string $feature4Title;
    public string $feature4Text;
    public string $feature5Title;
    public string $feature5Text;
    public string $feature5Icon;
    public string $feature5Url;
    public string $feature6Title;
    public string $feature6Text;
    public string $feature6Icon;
    public string $feature6Url;

    // Utility
    public string $frontHelpline;
    public array $notices = [];
    public string $frontWorkingHours;

    // CHO
    public string $choTitle;
    public string $choName;
    public string $choDescription;
    public string $choPhone;
    public string $choEmail;
    public string $choAddress;
    public string $choPost;
    public $choPhoto;
    public ?string $choPhotoPath = null;

    // Vision / Mission / Objectives
    public string $visionText;
    public string $missionText;
    public string $objectivesText;

    // Facts
    public string $fact1Value;
    public string $fact1Label;
    public string $fact2Value;
    public string $fact2Label;
    public string $fact3Value;
    public string $fact3Label;
    public string $fact4Value;
    public string $fact4Label;

    // Latest Updates
    public array $updates = [];

    // Info Ribbon
    public string $ribbon1Icon;
    public string $ribbon1Label;
    public string $ribbon2Icon;
    public string $ribbon2Label;
    public string $ribbon3Icon;
    public string $ribbon3Label;
    public string $ribbon4Icon;
    public string $ribbon4Label;

    // CTA
    public string $ctaIcon;
    public string $ctaTitle;
    public string $ctaSubtitle;
    public string $ctaBtnText;
    public string $ctaBtnUrl;

    // Meta / OG
    public string $metaTitle;
    public string $metaDescription;
    public string $metaKeywords;
    public $ogImage;
    public ?string $ogImagePath = null;

    // Lab Phone
    public string $labPhone;

    // Footer
    public string $footerAbout;
    public string $footerCopyright;
    public string $footerDisclaimer;
    public string $footerCreditText;
    public string $footerCreditUrl;

    public function mount()
    {
        $this->heroTitle = setting('front.hero_title', 'Soil Health & Testing Services');
        $this->heroSubtitle = setting('front.hero_subtitle', 'Scientific soil analysis for balanced nutrient management. Get your personalised Soil Health Card with crop-wise recommendations — free for all farmers.');
        $this->heroPhotoPath = setting('front.hero_photo_path', '');
        $this->heroPhotoPath2 = setting('front.hero_photo_path2', '');
        $this->heroPhotoPath3 = setting('front.hero_photo_path3', '');
        $this->heroBtnText = setting('front.hero_btn_text', 'Download Your Report');
        $this->heroBtnUrl = setting('front.hero_btn_url', route('public.track'));

        $this->headerMinistry = setting('front.header_ministry', 'Government of Jammu & Kashmir (UT)');
        $this->headerDeptEn = setting('front.header_dept_en', 'Horticulture Department');
        $this->headerDeptUr = setting('front.header_dept_ur', 'محکمہ باغبانی');
        $this->headerDeptHi = setting('front.header_dept_hi', 'बागवानी विभाग, जम्मू एवं कश्मीर');
        $this->headerEmblemPath = setting('front.header_emblem_path', '');
        $this->headerFlagPath = setting('front.header_flag_path', '');

        $this->noticeLabel = setting('front.notice_label', 'Notice');

        $this->feature1Title = setting('front.feature1_title', 'Sample Collection');
        $this->feature1Text = setting('front.feature1_text', 'Field officials collect soil samples from your farm with GPS-tagged locations.');
        $this->feature2Title = setting('front.feature2_title', 'Lab Testing');
        $this->feature2Text = setting('front.feature2_text', '12-parameter analysis including pH, NPK, micronutrients, and organic carbon.');
        $this->feature3Title = setting('front.feature3_title', 'Soil Health Card');
        $this->feature3Text = setting('front.feature3_text', 'Detailed report with test results, ratings, and crop-specific recommendations.');
        $this->feature4Title = setting('front.feature4_title', 'Fertilizer Advice');
        $this->feature4Text = setting('front.feature4_text', 'Science-based recommendations for balanced fertilizer application.');
        $this->feature5Title = setting('front.feature5_title', 'Locate Lab');
        $this->feature5Text = setting('front.feature5_text', 'Find your nearest soil testing laboratory.');
        $this->feature5Icon = setting('front.feature5_icon', 'fa-map-marked-alt');
        $this->feature5Url = setting('front.feature5_url', '#');
        $this->feature6Title = setting('front.feature6_title', 'Nutrient Guide');
        $this->feature6Text = setting('front.feature6_text', 'Balanced nutrient management recommendations.');
        $this->feature6Icon = setting('front.feature6_icon', 'fa-chart-bar');
        $this->feature6Url = setting('front.feature6_url', '#');

        $this->frontHelpline = setting('front.helpline', '1800-180-1551');
        $this->notices = json_decode(setting('front.notices', '[]'), true) ?: [];
        if (empty($this->notices)) {
            $legacy = setting('front.notice', '');
            $this->notices = $legacy ? [$legacy] : ['All soil testing services are provided free of cost to farmers under the Soil Health Card scheme.'];
        }
        $this->frontWorkingHours = setting('front.working_hours', 'Monday – Friday: 9:00 AM – 5:00 PM');

        $this->choTitle = setting('front.cho_title', 'Chief Horticulture Officer');
        $this->choName = setting('front.cho_name', '');
        $this->choDescription = setting('front.cho_description', 'The Horticulture Department, Pulwama is committed to promoting sustainable farming through scientific soil analysis, quality plant material, and modern horticultural practices. Farmers are encouraged to avail free soil testing services for better crop yield.');
        $this->choPhone = setting('front.cho_phone', '+91-XXXX-XXXXXX');
        $this->choEmail = setting('front.cho_email', 'cho.pulwama@jk.gov.in');
        $this->choAddress = setting('front.cho_address', 'Mini Secretariat, Pulwama, J&K 192301');
        $this->choPost = setting('front.cho_post', 'Pulwama District');
        $this->choPhotoPath = setting('front.cho_photo_path', '');

        $this->visionText = setting('front.vision_text', 'To strengthen an enabling environment to enhance soil health and agricultural productivity in Pulwama district through scientific testing, balanced nutrient management, and sustainable farming practices.');
        $this->missionText = setting('front.mission_text', 'Provide free, accessible soil testing to every farmer in Pulwama. Promote balanced fertiliser use, organic farming, and modern horticultural techniques to double farmer income.');
        $this->objectivesText = setting('front.objectives_text', 'Cover 100% of villages in Pulwama under soil testing. Reduce turnaround time. Issue Soil Health Cards with GPS-tagged recommendations. Train farmers on integrated nutrient management.');

        $this->fact1Value = setting('front.fact1_value', '14×');
        $this->fact1Label = setting('front.fact1_label', 'Growth Since 1972');
        $this->fact2Value = setting('front.fact2_value', '24.94 LMT');
        $this->fact2Label = setting('front.fact2_label', 'Annual Fruit Production');
        $this->fact3Value = setting('front.fact3_value', '23 Lakh+');
        $this->fact3Label = setting('front.fact3_label', 'People Employed');
        $this->fact4Value = setting('front.fact4_value', '₹6,300 Cr');
        $this->fact4Label = setting('front.fact4_label', 'Annual Turnover');

        $this->updates = json_decode(setting('front.updates', '[]'), true) ?: [];
        if (empty($this->updates)) {
            for ($i = 1; $i <= 5; $i++) {
                $text = setting('front.update'.$i.'_text', '');
                if ($text) {
                    $this->updates[] = ['text' => $text, 'date' => setting('front.update'.$i.'_date', '')];
                }
            }
        }
        if (empty($this->updates)) {
            $this->updates = [
                ['text' => 'Soil testing camp at Pulwama block on 15th July — farmers invited', 'date' => '10 Jun'],
                ['text' => 'Kharif season fertilizer subsidy — apply by 31st July 2026', 'date' => '05 Jun'],
                ['text' => 'Training on integrated nutrient management at Kakapora', 'date' => '28 May'],
                ['text' => 'New soil testing equipment installed at Pulwama lab', 'date' => '15 May'],
                ['text' => 'MIDH guidelines for 2026-27 released — download now', 'date' => '01 May'],
            ];
        }

        $this->ribbon1Icon = setting('front.ribbon1_icon', 'fa-hand-holding-heart');
        $this->ribbon1Label = setting('front.ribbon1_label', 'Free Soil Testing');
        $this->ribbon2Icon = setting('front.ribbon2_icon', 'fa-tachometer-alt');
        $this->ribbon2Label = setting('front.ribbon2_label', 'Quick Results');
        $this->ribbon3Icon = setting('front.ribbon3_icon', 'fa-language');
        $this->ribbon3Label = setting('front.ribbon3_label', 'Local Language Reports');
        $this->ribbon4Icon = setting('front.ribbon4_icon', 'fa-mobile-alt');
        $this->ribbon4Label = setting('front.ribbon4_label', 'Report via SMS');

        $this->labPhone = setting('front.lab_phone', '+91-XXXX-XXXXXX');

        $this->ctaIcon = setting('front.cta_icon', 'fa-search');
        $this->ctaTitle = setting('front.cta_title', 'Track Your Soil Report');
        $this->ctaSubtitle = setting('front.cta_subtitle', 'Enter your registered mobile number to check the status of your soil test and download your report.');
        $this->ctaBtnText = setting('front.cta_btn_text', 'Go to Report Tracking');
        $this->ctaBtnUrl = setting('front.cta_btn_url', route('public.track'));

        $this->metaTitle = setting('front.meta_title', '');
        $this->metaDescription = setting('front.meta_description', '');
        $this->metaKeywords = setting('front.meta_keywords', '');
        $this->ogImagePath = setting('front.og_image_path', '');

        $this->footerAbout = setting('front.footer_about', 'Official portal of the Soil Testing Laboratory, Pulwama under the Department of Agriculture & Farmers Welfare / Horticulture Department, Government of Jammu & Kashmir (UT). Soil Health Card — A Government of India initiative.');
        $this->footerCopyright = setting('front.footer_copyright', '&copy; ' . date('Y') . ' ' . setting('app_name', 'Soil Lab') . ' &mdash; Government of Jammu & Kashmir (UT)');
        $this->footerDisclaimer = setting('front.footer_disclaimer', 'This site is for information purposes only. Content is owned and maintained by the Horticulture Department, Pulwama District, J&K (UT).');
        $this->footerCreditText = setting('front.footer_credit_text', 'Designed and Developed by Umer Bashir');
        $this->footerCreditUrl = setting('front.footer_credit_url', 'https://theumer.store');
    }

    public function updatedHeroPhoto()
    {
        $this->validate(['heroPhoto' => 'image|mimes:jpg,jpeg,png|max:2048']);
    }

    public function removeHeroPhoto()
    {
        if ($this->heroPhotoPath) {
            Storage::disk('public')->delete($this->heroPhotoPath);
        }
        $this->heroPhotoPath = '';
        $this->heroPhoto = null;
        forget_setting('front.hero_photo_path');
    }

    public function updatedHeroPhoto2()
    {
        $this->validate(['heroPhoto2' => 'image|mimes:jpg,jpeg,png|max:2048']);
    }

    public function removeHeroPhoto2()
    {
        if ($this->heroPhotoPath2) {
            Storage::disk('public')->delete($this->heroPhotoPath2);
        }
        $this->heroPhotoPath2 = '';
        $this->heroPhoto2 = null;
        forget_setting('front.hero_photo_path2');
    }

    public function updatedHeroPhoto3()
    {
        $this->validate(['heroPhoto3' => 'image|mimes:jpg,jpeg,png|max:2048']);
    }

    public function removeHeroPhoto3()
    {
        if ($this->heroPhotoPath3) {
            Storage::disk('public')->delete($this->heroPhotoPath3);
        }
        $this->heroPhotoPath3 = '';
        $this->heroPhoto3 = null;
        forget_setting('front.hero_photo_path3');
    }

    public function updatedChoPhoto()
    {
        $this->validate(['choPhoto' => 'image|mimes:jpg,jpeg,png|max:1024']);
    }

    public function removeChoPhoto()
    {
        if ($this->choPhotoPath) {
            Storage::disk('public')->delete($this->choPhotoPath);
        }
        $this->choPhotoPath = '';
        $this->choPhoto = null;
        forget_setting('front.cho_photo_path');
    }

    public function updatedHeaderEmblem()
    {
        $this->validate(['headerEmblem' => 'image|mimes:jpg,jpeg,png,svg|max:1024']);
    }

    public function removeHeaderEmblem()
    {
        if ($this->headerEmblemPath) {
            Storage::disk('public')->delete($this->headerEmblemPath);
        }
        $this->headerEmblemPath = '';
        $this->headerEmblem = null;
        forget_setting('front.header_emblem_path');
    }

    public function updatedHeaderFlag()
    {
        $this->validate(['headerFlag' => 'image|mimes:jpg,jpeg,png,svg|max:1024']);
    }

    public function removeHeaderFlag()
    {
        if ($this->headerFlagPath) {
            Storage::disk('public')->delete($this->headerFlagPath);
        }
        $this->headerFlagPath = '';
        $this->headerFlag = null;
        forget_setting('front.header_flag_path');
    }

    public function updatedOgImage()
    {
        $this->validate(['ogImage' => 'image|mimes:jpg,jpeg,png|max:2048']);
    }

    public function removeOgImage()
    {
        if ($this->ogImagePath) {
            Storage::disk('public')->delete($this->ogImagePath);
        }
        $this->ogImagePath = '';
        $this->ogImage = null;
        forget_setting('front.og_image_path');
    }

    public function addNotice()
    {
        $this->notices[] = '';
    }

    public function removeNotice($index)
    {
        unset($this->notices[$index]);
        $this->notices = array_values($this->notices);
    }

    public function addUpdate()
    {
        $this->updates[] = ['text' => '', 'date' => ''];
    }

    public function removeUpdate($index)
    {
        unset($this->updates[$index]);
        $this->updates = array_values($this->updates);
    }

    public function save()
    {
        if ($this->heroPhoto) {
            $this->validate(['heroPhoto' => 'image|mimes:jpg,jpeg,png|max:2048']);
            $path = $this->heroPhoto->store('photos', 'public');
            if ($this->heroPhotoPath) {
                Storage::disk('public')->delete($this->heroPhotoPath);
            }
            $this->heroPhotoPath = $path;
        }

        if ($this->heroPhoto2) {
            $this->validate(['heroPhoto2' => 'image|mimes:jpg,jpeg,png|max:2048']);
            $path = $this->heroPhoto2->store('photos', 'public');
            if ($this->heroPhotoPath2) {
                Storage::disk('public')->delete($this->heroPhotoPath2);
            }
            $this->heroPhotoPath2 = $path;
        }

        if ($this->heroPhoto3) {
            $this->validate(['heroPhoto3' => 'image|mimes:jpg,jpeg,png|max:2048']);
            $path = $this->heroPhoto3->store('photos', 'public');
            if ($this->heroPhotoPath3) {
                Storage::disk('public')->delete($this->heroPhotoPath3);
            }
            $this->heroPhotoPath3 = $path;
        }

        if ($this->choPhoto) {
            $this->validate(['choPhoto' => 'image|mimes:jpg,jpeg,png|max:1024']);
            $path = $this->choPhoto->store('photos', 'public');
            if ($this->choPhotoPath) {
                Storage::disk('public')->delete($this->choPhotoPath);
            }
            $this->choPhotoPath = $path;
        }

        if ($this->headerEmblem) {
            $this->validate(['headerEmblem' => 'image|mimes:jpg,jpeg,png,svg|max:1024']);
            $path = $this->headerEmblem->store('photos', 'public');
            if ($this->headerEmblemPath) {
                Storage::disk('public')->delete($this->headerEmblemPath);
            }
            $this->headerEmblemPath = $path;
        }

        if ($this->ogImage) {
            $this->validate(['ogImage' => 'image|mimes:jpg,jpeg,png|max:2048']);
            $path = $this->ogImage->store('photos', 'public');
            if ($this->ogImagePath) {
                Storage::disk('public')->delete($this->ogImagePath);
            }
            $this->ogImagePath = $path;
        }

        if ($this->headerFlag) {
            $this->validate(['headerFlag' => 'image|mimes:jpg,jpeg,png,svg|max:1024']);
            $path = $this->headerFlag->store('photos', 'public');
            if ($this->headerFlagPath) {
                Storage::disk('public')->delete($this->headerFlagPath);
            }
            $this->headerFlagPath = $path;
        }

        set_setting('front.hero_title', $this->heroTitle);
        set_setting('front.hero_subtitle', $this->heroSubtitle);
        set_setting('front.hero_photo_path', $this->heroPhotoPath);
        set_setting('front.hero_photo_path2', $this->heroPhotoPath2);
        set_setting('front.hero_photo_path3', $this->heroPhotoPath3);
        set_setting('front.hero_btn_text', $this->heroBtnText);
        set_setting('front.hero_btn_url', $this->heroBtnUrl);

        set_setting('front.header_ministry', $this->headerMinistry);
        set_setting('front.header_dept_en', $this->headerDeptEn);
        set_setting('front.header_dept_ur', $this->headerDeptUr);
        set_setting('front.header_dept_hi', $this->headerDeptHi);
        set_setting('front.header_emblem_path', $this->headerEmblemPath);
        set_setting('front.header_flag_path', $this->headerFlagPath);

        set_setting('front.notice_label', $this->noticeLabel);

        set_setting('front.feature1_title', $this->feature1Title);
        set_setting('front.feature1_text', $this->feature1Text);
        set_setting('front.feature2_title', $this->feature2Title);
        set_setting('front.feature2_text', $this->feature2Text);
        set_setting('front.feature3_title', $this->feature3Title);
        set_setting('front.feature3_text', $this->feature3Text);
        set_setting('front.feature4_title', $this->feature4Title);
        set_setting('front.feature4_text', $this->feature4Text);
        set_setting('front.feature5_title', $this->feature5Title);
        set_setting('front.feature5_text', $this->feature5Text);
        set_setting('front.feature5_icon', $this->feature5Icon);
        set_setting('front.feature5_url', $this->feature5Url);
        set_setting('front.feature6_title', $this->feature6Title);
        set_setting('front.feature6_text', $this->feature6Text);
        set_setting('front.feature6_icon', $this->feature6Icon);
        set_setting('front.feature6_url', $this->feature6Url);

        set_setting('front.helpline', $this->frontHelpline);
        set_setting('front.notices', json_encode(array_values(array_filter($this->notices))));
        set_setting('front.working_hours', $this->frontWorkingHours);

        set_setting('front.cho_title', $this->choTitle);
        set_setting('front.cho_name', $this->choName);
        set_setting('front.cho_description', $this->choDescription);
        set_setting('front.cho_phone', $this->choPhone);
        set_setting('front.cho_email', $this->choEmail);
        set_setting('front.cho_address', $this->choAddress);
        set_setting('front.cho_post', $this->choPost);
        set_setting('front.cho_photo_path', $this->choPhotoPath);

        set_setting('front.vision_text', $this->visionText);
        set_setting('front.mission_text', $this->missionText);
        set_setting('front.objectives_text', $this->objectivesText);

        set_setting('front.fact1_value', $this->fact1Value);
        set_setting('front.fact1_label', $this->fact1Label);
        set_setting('front.fact2_value', $this->fact2Value);
        set_setting('front.fact2_label', $this->fact2Label);
        set_setting('front.fact3_value', $this->fact3Value);
        set_setting('front.fact3_label', $this->fact3Label);
        set_setting('front.fact4_value', $this->fact4Value);
        set_setting('front.fact4_label', $this->fact4Label);

        set_setting('front.updates', json_encode($this->updates));

        set_setting('front.ribbon1_icon', $this->ribbon1Icon);
        set_setting('front.ribbon1_label', $this->ribbon1Label);
        set_setting('front.ribbon2_icon', $this->ribbon2Icon);
        set_setting('front.ribbon2_label', $this->ribbon2Label);
        set_setting('front.ribbon3_icon', $this->ribbon3Icon);
        set_setting('front.ribbon3_label', $this->ribbon3Label);
        set_setting('front.ribbon4_icon', $this->ribbon4Icon);
        set_setting('front.ribbon4_label', $this->ribbon4Label);

        set_setting('front.lab_phone', $this->labPhone);

        set_setting('front.cta_icon', $this->ctaIcon);
        set_setting('front.cta_title', $this->ctaTitle);
        set_setting('front.cta_subtitle', $this->ctaSubtitle);
        set_setting('front.cta_btn_text', $this->ctaBtnText);
        set_setting('front.cta_btn_url', $this->ctaBtnUrl);

        set_setting('front.meta_title', $this->metaTitle);
        set_setting('front.meta_description', $this->metaDescription);
        set_setting('front.meta_keywords', $this->metaKeywords);
        set_setting('front.og_image_path', $this->ogImagePath);

        set_setting('front.footer_about', $this->footerAbout);
        set_setting('front.footer_copyright', $this->footerCopyright);
        set_setting('front.footer_disclaimer', $this->footerDisclaimer);
        set_setting('front.footer_credit_text', $this->footerCreditText);
        set_setting('front.footer_credit_url', $this->footerCreditUrl);

        session()->flash('success', 'Front page content updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.front-page-manager');
    }
}

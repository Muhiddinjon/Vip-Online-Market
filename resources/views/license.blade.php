<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIP Online Market – License Agreement</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f4f6f9;
            color: #2d3748;
            min-height: 100vh;
        }

        .page-wrapper {
            max-width: 860px;
            margin: 0 auto;
            padding: 40px 20px 60px;
        }

        /* Header */
        .header {
            position: relative;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            border-radius: 16px;
            padding: 36px 40px 32px;
            margin-bottom: 32px;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: rgba(229, 57, 53, 0.12);
            border-radius: 50%;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -30px;
            width: 240px;
            height: 240px;
            background: rgba(229, 57, 53, 0.07);
            border-radius: 50%;
        }

        .lang-switcher {
            position: absolute;
            top: 20px;
            right: 24px;
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .lang-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            padding: 6px 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .lang-btn:hover {
            background: rgba(255,255,255,0.16);
            border-color: rgba(255,255,255,0.35);
            color: #fff;
            transform: translateY(-1px);
        }

        .lang-btn.active {
            background: rgba(229, 57, 53, 0.3);
            border-color: #e53935;
            color: #fff;
        }

        .flag {
            font-size: 18px;
            line-height: 1;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .logo-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #e53935, #c62828);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            box-shadow: 0 4px 16px rgba(229,57,53,0.4);
            flex-shrink: 0;
        }

        .header h1 {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
            position: relative;
            z-index: 1;
        }

        .header h1 span {
            color: #ef9a9a;
            font-size: 14px;
            font-weight: 400;
            display: block;
            margin-top: 4px;
        }

        .intro-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 16px 20px;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }

        .intro-card p {
            color: rgba(255,255,255,0.75);
            font-size: 13.5px;
            line-height: 1.7;
            margin-bottom: 6px;
        }

        .intro-card p:last-child { margin-bottom: 0; }

        .intro-card p strong {
            color: #ef9a9a;
        }

        /* Content */
        .content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .section-card {
            background: #fff;
            border-radius: 12px;
            padding: 28px 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 12px rgba(0,0,0,0.04);
            border: 1px solid #e8edf3;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid #f0f4f8;
        }

        .section-number {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #e53935, #c62828);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .section-title h2 {
            font-size: 16px;
            font-weight: 700;
            color: #1a202c;
        }

        .clause {
            margin-bottom: 14px;
            display: flex;
            gap: 12px;
        }

        .clause:last-child { margin-bottom: 0; }

        .clause-num {
            font-size: 13px;
            font-weight: 700;
            color: #e53935;
            min-width: 36px;
            padding-top: 1px;
            flex-shrink: 0;
        }

        .clause p {
            font-size: 14px;
            line-height: 1.75;
            color: #4a5568;
        }

        .clause p strong {
            color: #2d3748;
        }

        /* Footer */
        .page-footer {
            text-align: center;
            margin-top: 36px;
            color: #a0aec0;
            font-size: 12.5px;
        }

        .page-footer a {
            color: #e53935;
            text-decoration: none;
        }

        /* Hidden lang sections */
        .lang-section {
            display: none;
        }
        .lang-section.active {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        @media (max-width: 600px) {
            .page-wrapper { padding: 20px 14px 40px; }
            .header { padding: 56px 20px 24px; }
            .lang-switcher { top: 16px; right: 16px; gap: 6px; }
            .lang-btn { padding: 5px 9px; font-size: 12px; }
            .lang-btn .label { display: none; }
            .section-card { padding: 20px 18px; }
            .header h1 { font-size: 18px; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <!-- HEADER -->
    <div class="header">
        <div class="lang-switcher">
            <button class="lang-btn active" onclick="switchLang('en')" id="btn-en">
                <span class="flag">🇬🇧</span>
                <span class="label">EN</span>
            </button>
            <button class="lang-btn" onclick="switchLang('tr')" id="btn-tr">
                <span class="flag">🇹🇷</span>
                <span class="label">TR</span>
            </button>
            <button class="lang-btn" onclick="switchLang('uz')" id="btn-uz">
                <span class="flag">🇺🇿</span>
                <span class="label">UZ</span>
            </button>
        </div>

        <div class="logo-area">
            <div class="logo-icon">🛒</div>
            <h1>
                VIP Online Market
                <span id="header-sub">License Agreement · Mobile Application</span>
            </h1>
        </div>

        <div class="intro-card">
            <p id="intro-1">Please read the following license agreement terms carefully before using the application.</p>
            <p id="intro-2">Any use of the application in any manner constitutes <strong>full and unconditional acceptance</strong> of the terms of this license agreement.</p>
            <p id="intro-3">If you do not fully accept the license agreement, you may not use the application for any purpose.</p>
        </div>
    </div>

    <!-- ==================== ENGLISH ==================== -->
    <div class="lang-section active" id="lang-en">

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">1</div>
                <h2>General Provisions</h2>
            </div>
            <div class="clause">
                <span class="clause-num">1.1</span>
                <p>This License Agreement (hereinafter referred to as the <strong>"License"</strong>) defines the terms of use of the <strong>"VIP Online Market"</strong> mobile application (hereinafter referred to as the <strong>"Application"</strong>) and is concluded with each individual or legal entity using this application.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.2</span>
                <p>By copying, installing on a mobile device, or using the Application for any purpose, the User declares full and unconditional acceptance of all terms of the License.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.3</span>
                <p>The Application may only be used in accordance with the terms of this License. If the User does not fully accept the License terms, the User may not use the Application for any purpose. Use of the Application in a manner that violates (fails to fulfill) any term of this License is prohibited.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.4</span>
                <p>The Application may be used by the User free of charge for personal and non-commercial purposes in accordance with the terms and conditions of this License. Use of the Application under terms and methods not provided for in this License is only possible on the basis of a separate agreement with the Rights Holder.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.5</span>
                <p>The terms of the Application (including any part thereof) may be changed by the Rights Holder without prior special notice. New versions of the documents shall take effect from the date of publication, unless otherwise provided in the text.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.7</span>
                <p>By using the Application, the User consents to receiving informational messages, including short messages (SMS) sent to the User's mobile phone number.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">3</div>
                <h2>License</h2>
            </div>
            <div class="clause">
                <span class="clause-num">3.1</span>
                <p>The Rights Holder grants the User, on the basis of a free and simple (non-exclusive) license, a non-transferable right to use the Application in all countries of the world in the following ways:</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.1.1</span>
                <p>Use of the Application for its intended purpose, including copying and installing (reproducing) it on the User's mobile device. The User may install the Application on an unlimited number of mobile devices;</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.1.2</span>
                <p>Reproduction and distribution of the Application for non-commercial purposes (free of charge).</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.2</span>
                <p>The Application is used (including distributed) under the name <strong>"VIP Online Market"</strong>. The User may not modify and/or remove the name of the Application, copyright notices, or references/links to the Rights Holder.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">4</div>
                <h2>Restrictions</h2>
            </div>
            <div class="clause">
                <span class="clause-num">4.1</span>
                <p>Beyond the scope and methods expressly permitted in this License, the User may not: modify the source code and object code of the application, decompile, disassemble, decrypt, or perform other actions aimed at obtaining information about the algorithms of the application. Creation of derivative works from the Application, as well as use (or permitting use) of the Application or any of its components, maps, and other visuals and data stored by the Application on the User's mobile device in other ways, is prohibited without the written permission of the Rights Holder.</p>
            </div>
            <div class="clause">
                <span class="clause-num">4.2</span>
                <p>The User may not reproduce and distribute the Application for commercial purposes (in particular, for a fee as part of a software package/set of products) without the written permission of the Rights Holder.</p>
            </div>
            <div class="clause">
                <span class="clause-num">4.3</span>
                <p>The User may not distribute the Application in any form other than the form in which it was received, without the written permission of the Rights Holder.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">5</div>
                <h2>Special Provisions</h2>
            </div>
            <div class="clause">
                <span class="clause-num">5.1</span>
                <p>Some functions of the Application can only be performed when there is an active internet connection. The User independently provides and pays for such access in accordance with the terms and tariffs of their GSM operator or internet service provider.</p>
            </div>
            <div class="clause">
                <span class="clause-num">5.2</span>
                <p><strong>Sharing of Personal Data:</strong> By using and/or registering in the Application, the User explicitly accepts, declares, and undertakes that their personal data shared by them or automatically collected by the Application (contact information, location data, order history, etc.) may be shared with third-party individuals, organizations, business partners, and entities for the purposes of improving service quality, establishing business partnerships, advertising, marketing, and conducting operational processes.</p>
            </div>
        </div>

    </div><!-- /lang-en -->

    <!-- ==================== TURKISH ==================== -->
    <div class="lang-section" id="lang-tr">

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">1</div>
                <h2>Genel Hükümler</h2>
            </div>
            <div class="clause">
                <span class="clause-num">1.1</span>
                <p>İşbu Lisans Sözleşmesi (bundan sonra <strong>"Lisans"</strong> olarak anılacaktır), mobil cihazlar için <strong>"VIP Online Market"</strong> uygulamasının (bundan sonra <strong>"Uygulama"</strong> olarak anılacaktır) kullanım şartlarını belirler ve bu uygulamayı kullanan her bir gerçek veya tüzel kişi ile akdedilir.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.2</span>
                <p>Uygulamayı kopyalayarak, mobil cihazına yükleyerek veya Uygulamayı herhangi bir amaçla kullanarak Kullanıcı, Lisansın tüm şartlarını tam ve kayıtsız şartsız kabul ettiğini beyan eder.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.3</span>
                <p>Uygulamanın kullanımına yalnızca bu Lisans şartlarına uygun olarak izin verilir. Kullanıcı Lisans şartlarını tamamen kabul etmezse, Kullanıcı Uygulamayı hiçbir amaçla kullanamaz. Uygulamanın, Lisansın herhangi bir şartını ihlal edecek (yerine getirmeyecek) şekilde kullanılması yasaktır.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.4</span>
                <p>Uygulama, Kullanıcı tarafından bu Lisansın hüküm ve şartlarına uygun olarak kişisel ve ticari olmayan amaçlarla ücretsiz olarak kullanılabilir. Uygulamanın, bu Lisansta öngörülmeyen şartlar ve yöntemlerle kullanılması ancak Hak Sahibi ile yapılacak ayrı bir sözleşme temelinde mümkündür.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.5</span>
                <p>Uygulama şartları (herhangi bir parçası dahil olmak üzere), Hak Sahibi tarafından önceden özel bir bildirimde bulunulmaksızın değiştirilebilir. Belgelerin yeni sürümleri, metinde aksine bir hüküm bulunmadıkça, yayınlandıkları tarihten itibaren yürürlüğe girer.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.7</span>
                <p>Uygulamayı kullanarak Kullanıcı, kullanıcının mobil telefon numarasına gönderilecek kısa mesajlar (SMS) dahil olmak üzere bilgilendirici mesajlar almayı kabul eder.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">3</div>
                <h2>Lisans</h2>
            </div>
            <div class="clause">
                <span class="clause-num">3.1</span>
                <p>Hak Sahibi, ücretsiz ve basit (münhasır olmayan) bir lisans temelinde Kullanıcıya, Uygulamayı dünyanın tüm ülkelerinde aşağıdaki şekillerde kullanması için devredilemez bir hak verir:</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.1.1</span>
                <p>Uygulamanın amaçlanan doğrultuda kullanılması, dolayısıyla kullanıcının mobil cihazına kopyalanması ve yüklenmesi (çoğaltılması). Kullanıcı, Uygulamayı sınırsız sayıda mobil cihaza yükleyebilir;</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.1.2</span>
                <p>Uygulamanın ticari olmayan amaçlarla çoğaltılması ve dağıtılması (ücretsiz olarak).</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.2</span>
                <p>Uygulama <strong>"VIP Online Market"</strong> adı altında kullanılır (dağıtımı dahil). Kullanıcı; Uygulamanın adını, telif hakkı bildirimini veya Hak Sahibine yapılan atıfları/bağlantıları değiştiremez ve/veya kaldıramaz.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">4</div>
                <h2>Kısıtlamalar</h2>
            </div>
            <div class="clause">
                <span class="clause-num">4.1</span>
                <p>İşbu Lisansta açıkça belirtilen kapsam ve yöntemler dışında Kullanıcı; uygulamanın kaynak kodunu ve nesne kodunu değiştiremez, kaynak koda dönüştüremez (decompile), tersine mühendislik yapamaz (disassemble), şifresini çözemez ve uygulamanın algoritmaları hakkında bilgi edinmeye yönelik diğer eylemleri gerçekleştiremez. Hak Sahibinin yazılı izni olmaksızın Uygulamadan türev eserler oluşturulması ve Uygulamanın veya herhangi bir bileşeninin, haritalarının ve Uygulama tarafından kullanıcının mobil cihazında saklanan diğer görsel ve verilerin başka şekillerde kullanılması (kullanılmasına izin verilmesi) yasaktır.</p>
            </div>
            <div class="clause">
                <span class="clause-num">4.2</span>
                <p>Kullanıcı, Hak Sahibinin yazılı izni olmaksızın Uygulamayı ticari amaçlarla (özellikle bir yazılım paketi/ürünleri setinin parçası olarak ücret karşılığında) çoğaltamaz ve dağıtamaz.</p>
            </div>
            <div class="clause">
                <span class="clause-num">4.3</span>
                <p>Kullanıcı, Hak Sahibinin yazılı izni olmaksızın Uygulamayı teslim aldığı biçimden başka herhangi bir biçimde dağıtamaz.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">5</div>
                <h2>Özel Hükümler</h2>
            </div>
            <div class="clause">
                <span class="clause-num">5.1</span>
                <p>Uygulamanın bazı işlevleri yalnızca aktif bir internet bağlantısı olduğunda gerçekleştirilebilir. Kullanıcı, kendi GSM operatörü veya internet servis sağlayıcısının şart ve tariflerine göre bu erişimi bağımsız olarak sağlar ve ücretini öder.</p>
            </div>
            <div class="clause">
                <span class="clause-num">5.2</span>
                <p><strong>Kişisel Verilerin Paylaşımı:</strong> Kullanıcı, Uygulamayı kullanarak ve/veya Uygulamaya kayıt olarak, paylaştığı veya Uygulama tarafından otomatik olarak toplanan kişisel verilerinin (iletişim bilgileri, konum verileri, sipariş geçmişi vb.) hizmet kalitesinin artırılması, iş ortaklıkları kurulması, reklam, pazarlama ve operasyonel süreçlerin yürütülmesi amacıyla 3. taraf kişiler, kurumlar, iş ortakları ve şahıslarla paylaşılabileceğini açıkça kabul, beyan ve taahhüt eder.</p>
            </div>
        </div>

    </div><!-- /lang-tr -->

    <!-- ==================== UZBEK ==================== -->
    <div class="lang-section" id="lang-uz">

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">1</div>
                <h2>Umumiy qoidalar</h2>
            </div>
            <div class="clause">
                <span class="clause-num">1.1</span>
                <p>Ushbu Litsenziya Shartnomasi (bundan buyon matnda <strong>"Litsenziya"</strong> deb yuritiladi) mobil qurilmalar uchun <strong>"VIP Online Market"</strong> ilovasidan (bundan buyon matnda <strong>"Ilova"</strong> deb yuritiladi) foydalanish shartlarini belgilaydi va ushbu ilovadan foydalanuvchi har bir jismoniy yoki yuridik shaxs bilan tuziladi.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.2</span>
                <p>Ilovadan nusxa ko'chirish, uni mobil qurilmaga o'rnatish yoki Ilovadan har qanday maqsadda foydalanish orqali Foydalanuvchi Litsenziyaning barcha shartlarini to'liq va so'zsiz qabul qilganini bildiradi.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.3</span>
                <p>Ilovadan faqat ushbu Litsenziya shartlariga muvofiq foydalanishga ruxsat beriladi. Agar Foydalanuvchi Litsenziya shartlarini to'liq qabul qilmasa, Foydalanuvchi Ilovadan hech qanday maqsadda foydalana olmaydi. Ilovadan ushbu Litsenziyaning biron bir shartini buzadigan (bajarmaydigan) tarzda foydalanish taqiqlanadi.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.4</span>
                <p>Ilova Foydalanuvchi tomonidan ushbu Litsenziya qoidalari va shartlariga muvofiq shaxsiy va tijorat bo'lmagan maqsadlarda bepul foydalanilishi mumkin. Ilovadan ushbu Litsenziyada ko'zda tutilmagan shartlar va usullar bilan foydalanish faqat Huquq egasi bilan alohida shartnoma tuzish asosidagina amalga oshirilishi mumkin.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.5</span>
                <p>Ilova shartlari (uning har qanday qismi ham) Huquq egasi tomonidan oldindan maxsus ogohlantirishsiz o'zgartirilishi mumkin. Hujjatlarning yangi tahrirlari, agar matnda boshqa holat ko'zda tutilmagan bo'lsa, e'lon qilingan sanadan boshlab kuchga kiradi.</p>
            </div>
            <div class="clause">
                <span class="clause-num">1.7</span>
                <p>Ilovadan foydalanish orqali Foydalanuvchi o'zining mobil telefon raqamiga yuboriladigan qisqa xabarlar (SMS) bilan birga turli xildagi axborot va bildirishnoma xabarlarini olishga rozilik beradi.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">3</div>
                <h2>Litsenziya</h2>
            </div>
            <div class="clause">
                <span class="clause-num">3.1</span>
                <p>Huquq egasi bepul va oddiy (mutlaq bo'lmagan) litsenziya asosida Foydalanuvchiga Ilovadan dunyoning barcha mamlakatlarida quyidagi usullarda foydalanish bo'yicha boshqa shaxsga o'tkazilmaydigan huquqni beradi:</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.1.1</span>
                <p>Ilovadan belgilangan maqsadda foydalanish, shu jumladan uni foydalanuvchining mobil qurilmasiga ko'chirish va o'rnatish (ko'paytirish). Foydalanuvchi Ilovani cheklanmagan miqdordagi mobil qurilmalarga o'rnatishi mumkin;</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.1.2</span>
                <p>Ilovani tijorat bo'lmagan maqsadlarda ko'paytirish va tarqatish (bepul asosda).</p>
            </div>
            <div class="clause">
                <span class="clause-num">3.2</span>
                <p>Ilova <strong>"VIP Online Market"</strong> nomi ostida foydalaniladi (shu jumladan tarqatiladi). Foydalanuvchi Ilovaning nomini, mualliflik huquqi to'g'risidagi ogohlantirishni yoki Huquq egasiga bo'lgan havolalar/ishoralarni o'zgartirishi va/yoki olib tashlashi mumkin emas.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">4</div>
                <h2>Cheklovlar</h2>
            </div>
            <div class="clause">
                <span class="clause-num">4.1</span>
                <p>Ushbu Litsenziyada aniq ko'rsatilgan ruxsat etilgan doira va usullardan tashqari Foydalanuvchi: ilovaning dasturiy kodini (manba kodi va obyekt kodi) o'zgartirishi, dekompilatsiya qilishi (decompile), teskari muhandislikni amalga oshirishi (disassemble), shifrini ochishi va ilova algoritmlari haqida ma'lumot olishga qaratilgan boshqa harakatlarni bajarishi mumkin emas. Huquq egasining yozma ruxsatisiz Ilovadan hosila asarlar yaratish, shuningdek Ilova yoki uning biron bir komponenti, xaritalari va Ilova tomonidan foydalanuvchining mobil qurilmasida saqlanadigan boshqa vizual hamda ma'lumotlardan boshqa shakllarda foydalanish (foydalanishga ruxsat berish) taqiqlanadi.</p>
            </div>
            <div class="clause">
                <span class="clause-num">4.2</span>
                <p>Foydalanuvchi Huquq egasining yozma ruxsatisiz Ilovani tijorat maqsadlarida (xususan, dasturiy ta'minot paketi/mahsulotlar to'plamining bir qismi sifatida haq evaziga) ko'paytirishi va tarqatishi mumkin emas.</p>
            </div>
            <div class="clause">
                <span class="clause-num">4.3</span>
                <p>Foydalanuvchi Huquq egasining yozma ruxsatisiz Ilovani qabul qilib olgan shaklidan boshqa har qanday shaklda tarqatishi taqiqlanadi.</p>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <div class="section-number">5</div>
                <h2>Maxsus qoidalar</h2>
            </div>
            <div class="clause">
                <span class="clause-num">5.1</span>
                <p>Ilovaning ba'zi funksiyalari faqat faol internet aloqasi mavjud bo'lgandagina amalga oshiriladi. Foydalanuvchi ushbu internet aloqasini o'zining GSM operatori yoki internet provayderining shartlari hamda tariflariga muvofiq mustaqil ravishda ta'minlaydi va to'lovini amalga oshiradi.</p>
            </div>
            <div class="clause">
                <span class="clause-num">5.2</span>
                <p><strong>Shaxsiy ma'lumotlarni uchinchi shaxslarga ulashish:</strong> Foydalanuvchi Ilovadan foydalanish va/yoki Ilovada ro'yxatdan o'tish orqali o'zi ulashgan yoki Ilova tomonidan avtomatik ravishda yig'ilgan shaxsiy ma'lumotlari (aloqa ma'lumotlari, joylashuv ma'lumotlari, buyurtmalar tarixi va h.k.) xizmat ko'rsatish sifatini oshirish, biznes hamkorlik aloqalarini o'rnatish, reklama, marketing va operatsion jarayonlarni amalga oshirish maqsadida uchinchi tomon jismoniy shaxslari, tashkilotlar, biznes hamkorlar va subyektlar bilan baham ko'rilishi (ulashilishi) mumkinligini aniq va so'zsiz qabul qiladi, bayon etadi va majburiyat oladi.</p>
            </div>
        </div>

    </div><!-- /lang-uz -->

    <div class="page-footer">
        &copy; 2026 VIP Online Market. All rights reserved.
    </div>

</div><!-- /page-wrapper -->

<script>
    const translations = {
        en: {
            sub: 'License Agreement · Mobile Application',
            intro1: 'Please read the following license agreement terms carefully before using the application.',
            intro2: 'Any use of the application in any manner constitutes <strong>full and unconditional acceptance</strong> of the terms of this license agreement.',
            intro3: 'If you do not fully accept the license agreement, you may not use the application for any purpose.',
        },
        tr: {
            sub: 'Lisans Sözleşmesi · Mobil Uygulama',
            intro1: 'Uygulamayı kullanmadan önce lütfen aşağıdaki lisans sözleşmesi şartlarını okuyunuz.',
            intro2: 'Uygulamanın herhangi bir şekilde kullanılması, bu lisans sözleşmesi şartlarının <strong>tam ve kayıtsız şartsız kabul edildiği</strong> anlamına gelir.',
            intro3: 'Lisans sözleşmesini tamamen kabul etmiyorsanız, uygulamayı hiçbir amaçla kullanamazsınız.',
        },
        uz: {
            sub: 'Litsenziya Shartnomasi · Mobil Ilova',
            intro1: 'Ilovadan foydalanishni boshlashdan oldin iltimos quyidagi litsenziya shartnomasi shartlari bilan tanishib chiqing.',
            intro2: 'Ilovadan har qanday tarzda foydalanish, ushbu litsenziya shartnomasi shartlarini <strong>to\'liq va so\'zsiz qabul qilganingizni</strong> anglatadi.',
            intro3: 'Agar litsenziya shartnomasini to\'liq qabul qilmasangiz, ilovadan hech qanday maqsadda foydalana olmaysiz.',
        }
    };

    function switchLang(lang) {
        // Hide all sections
        document.querySelectorAll('.lang-section').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.lang-btn').forEach(el => el.classList.remove('active'));

        // Show selected
        document.getElementById('lang-' + lang).classList.add('active');
        document.getElementById('btn-' + lang).classList.add('active');

        // Update header text
        const t = translations[lang];
        document.getElementById('header-sub').textContent = t.sub;
        document.getElementById('intro-1').textContent = t.intro1;
        document.getElementById('intro-2').innerHTML = t.intro2;
        document.getElementById('intro-3').textContent = t.intro3;
    }
</script>

</body>
</html>

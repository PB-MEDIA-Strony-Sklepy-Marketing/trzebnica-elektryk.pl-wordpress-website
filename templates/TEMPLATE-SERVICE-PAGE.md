# Template: Nowa Strona Usługi

**Nazwa usługi:** [NAZWA USŁUGI]  
**Slug URL:** `/uslugi/[slug-uslugi]/`  
**Data utworzenia:** [DATA]

---

## Meta Informacje SEO

**Title Tag (50-60 znaków):**
```
[Nazwa Usługi] Trzebnica - Dolny Śląsk | Voltmont
```

**Meta Description (150-160 znaków):**
```
[Opis usługi z korzyściami]. Profesjonalna realizacja w Trzebnicy i całym Dolnym Śląsku. ✓ Doświadczenie ✓ Certyfikaty ✓ Gwarancja. Tel: +48 691 594 820
```

**Keywords (docelowe):**
- `[usługa] Trzebnica`
- `[usługa] Dolny Śląsk`
- `[usługa] Wrocław`
- `elektryk [usługa]`

---

## Nagłówek Strony

### H1 (główny nagłówek)
```
[Nazwa Usługi] w Trzebnicy i Dolnym Śląsku
```

### Wprowadzenie (excerpt)
```
[Krótki opis usługi, 2-3 zdania. Powinien zawierać główne słowa kluczowe i wyjaśniać wartość dla klienta]
```

**Przykład:**
> Oferujemy profesjonalne instalacje odgromowe dla budynków mieszkalnych i komercyjnych na Dolnym Śląsku. Nasze rozwiązania chronią Twój obiekt przed skutkami wyładowań atmosferycznych. Posiadamy wszystkie niezbędne certyfikaty i wieloletnie doświadczenie.

---

## Struktura Treści

### 1. Czym jest [Nazwa Usługi]?

**H2:** Czym jest [nazwa usługi]?

```markdown
[Wyjaśnij czym jest ta usługa, dlaczego jest potrzebna, jakie problemy rozwiązuje]

**Kluczowe elementy:**
- [Element 1]
- [Element 2]
- [Element 3]
- [Element 4]
```

---

### 2. Zakres Usług

**H2:** Co obejmuje nasza oferta?

**H3:** [Podusługa 1]
```
[Opis podusługi 1]
```

**H3:** [Podusługa 2]
```
[Opis podusługi 2]
```

**H3:** [Podusługa 3]
```
[Opis podusługi 3]
```

---

### 3. Proces Realizacji

**H2:** Jak wygląda realizacja?

```markdown
1. **Konsultacja i wycena**  
   Bezpłatna konsultacja, określenie zakresu prac, przygotowanie dokładnej wyceny.

2. **Projekt techniczny**  
   Opracowanie projektu dostosowanego do specyfiki obiektu i przepisów.

3. **Realizacja**  
   Profesjonalny montaż przez wykwalifikowany zespół elektryków.

4. **Odbiór i dokumentacja**  
   Pomiary, testy, protokoły odbiorcze oraz dokumentacja powykonawcza.

5. **Gwarancja i serwis**  
   Pełna gwarancja na wykonane prace oraz serwis pog warancyjny.
```

---

### 4. Dlaczego Warto Wybrać Voltmont?

**H2:** Dlaczego warto nam zaufać?

```markdown
✓ **[Korzyść 1]** - [Opis]
✓ **[Korzyść 2]** - [Opis]
✓ **[Korzyść 3]** - [Opis]
✓ **[Korzyść 4]** - [Opis]
```

**Przykład:**
```markdown
✓ **Wieloletnie doświadczenie** - Ponad 15 lat na rynku instalacji elektrycznych  
✓ **Certyfikaty i uprawnienia** - Posiadamy wszystkie wymagane certyfikaty  
✓ **Kompleksowa realizacja** - Od projektu po odbiór i dokumentację  
✓ **Konkurencyjne ceny** - Transparentne wyceny bez ukrytych kosztów
```

---

### 5. FAQ - Najczęściej Zadawane Pytania

**H2:** Najczęściej zadawane pytania

Użyj WordPress Meta Box "FAQ dla Schema.org" lub dodaj ręcznie:

```markdown
**H3:** Ile kosztuje [nazwa usługi]?
Koszt zależy od [czynniki]. Oferujemy bezpłatną wycenę po oględzinach obiektu. Średnie ceny wynoszą od [X] do [Y] PLN.

**H3:** Jak długo trwa realizacja?
Typowa realizacja zajmuje od [X] do [Y] dni roboczych, w zależności od zakresu prac.

**H3:** Czy potrzebne są jakieś pozwolenia?
[Odpowiedź dotycząca pozwoleń i formalności]

**H3:** Czy oferujecie gwarancję?
Tak, na wszystkie wykonane prace udzielamy [X] lat gwarancji.

**H3:** Gdzie świadczycie usługi?
Działamy na terenie całego Dolnego Śląska, w szczególności: Trzebnica, Wrocław, Oleśnica, Oborniki Śląskie.
```

---

## Call to Action

### Sekcja CTA (na końcu strony)

```markdown
## Potrzebujesz [nazwa usługi]? Skontaktuj się z nami!

Oferujemy bezpłatną konsultację i wycenę. Zadzwoń lub napisz już dziś!

[PRZYCISK: Zadzwoń: +48 691 594 820]
[PRZYCISK: Wyślij zapytanie]
```

---

## Elementy Wizualne

### Obrazy do dodania:

1. **Zdjęcie główne (hero image)**
   - Wymiary: 1920x1080px (WebP)
   - Alt text: "[Nazwa usługi] - realizacja Voltmont Trzebnica"

2. **Zdjęcia procesu (3-5 zdjęć)**
   - Wymiary: 800x600px (WebP)
   - Alt text: opisujący każde zdjęcie

3. **Ikona usługi**
   - Format: SVG lub PNG
   - Do użycia w kartach usług

---

## Schema.org Markup

Automatyczne generowanie przez funkcję `voltmont_get_service_schema()` w `inc/schema-localbusiness.php`.

**Potwierdź po publikacji:**
- https://search.google.com/test/rich-results
- https://validator.schema.org/

---

## Internal Linking

**Linki do innych stron usług:**
```
Zobacz też:
- [Instalacje elektryczne](/uslugi/instalacje-elektryczne/)
- [Modernizacja instalacji](/uslugi/modernizacja-instalacji/)
- [Nadzór elektryczny](/uslugi/nadzor-elektryczny/)
```

**Link do portfolio:**
```
[Zobacz nasze realizacje w kategorii: [kategoria]](/galeria-realizacji-kategoria/[slug]/)
```

---

## Ustawienia WordPress

### Taxonomies
- **Kategoria strony (`page_category`):** `uslugi-elektryczne`
- **Tagi (`page_tag`):** `[tag1]`, `[tag2]`, `[tag3]`

### Muffin Builder Settings
- **Template:** Service Page Template
- **Layout:** Full Width
- **Sidebar:** None

### Excerpt
Użyj wprowadzenia jako excerpta (zostanie wyświetlone przed treścią).

---

## Checklist Publikacji

- [ ] Tytuł strony ustawiony (H1)
- [ ] Meta description dodany (150-160 znaków)
- [ ] Excerpt wypełniony
- [ ] Treść napisana (min. 800 słów)
- [ ] FAQ dodane (min. 4 pytania)
- [ ] Obrazy dodane i zoptymalizowane (WebP)
- [ ] Alt text dla wszystkich obrazów
- [ ] Internal linking dodany (min. 3 linki)
- [ ] CTA buttons dodane
- [ ] Kategoria `page_category` przypisana
- [ ] Tagi dodane
- [ ] Schema.org sprawdzone
- [ ] Mobile responsiveness sprawdzona
- [ ] Opublikowane i sprawdzone na żywo

---

**Data aktualizacji templatu:** 2024-01-15  
**Wersja:** 1.0

# IT Passport (ITPEC IP) — Official Examination Alignment & Implementation Plan

> **Specification Source**: [Official ITPEC Common Examination Outline (Effective April 2024)](https://www.itpec.org/about/Outline_of_ITPEC_Common_Examination-fromApril2024.pdf)

---

## 1. Executive Summary & Gap Analysis

The official ITPEC IT Passport Examination (conducted in English in participating countries like the Philippines via PhilNITS) follows a precise 3-field structure with strict grading thresholds and format requirements that were partially unaligned in the application codebase.

| Area | Official ITPEC IP Requirement | Previous App State | Implementation Priority |
| :--- | :--- | :--- | :--- |
| **Field Architecture** | 3 Official Fields:<br>• **Technology** (~40%)<br>• **Management** (~25%)<br>• **Strategy** (~35%) | 4 flat unlinked topics (`ITF`, `BM`, `TECH`, `LC`) with no canonical field association. | **Phase 1** (High) |
| **Passing Standard** | **Overall $\ge 60\%$** **AND**<br>• Technology $\ge 30\%$<br>• Management $\ge 30\%$<br>• Strategy $\ge 30\%$ | Only checks `score >= 60%`. If a user scored 90% in Tech but 20% in Strategy, the app marked it as "Passed". | **Phase 1** (High) |
| **Assessment Results Breakdown** | Displays performance across all 3 fields against their 30% minimum thresholds. | Only displays a flat list of individual topics. | **Phase 2** (High) |
| **Full Mock Exam Simulation** | **100 Multiple-Choice Questions**, 4 options each, **120-minute countdown** (~72s / question). | Untimed / simple elapsed stopwatch without 100Q mock mode. | **Phase 3** (Medium) |
| **Pseudoprogramming Language Questions** | Questions assessing programming logic using pseudocode (added in April 2024). | Plain text questions only without syntax/monospaced formatting blocks. | **Phase 4** (Medium) |

---

## 2. Implementation Roadmap

### Phase 1: Canonical 3-Field Data Architecture
- [ ] Add `field` column (`'strategy'`, `'management'`, `'technology'`) to `topics` table (or migration).
- [ ] Map existing and new topics to their official field:
  - **Strategy (S)**: Corporate strategy, business activities, accounting & finance, legal affairs & compliance, information systems strategy.
  - **Management (M)**: Project management, software development techniques, service management, operations & maintenance, risk management.
  - **Technology (T)**: Computer hardware/software, networks & communication, database systems, information security, programming logic / algorithms.
- [ ] Update `Topic` model with helper scopes (`scopeStrategy`, `scopeManagement`, `scopeTechnology`) and accessor for field label and badge color.

### Phase 2: Official Grading Engine (Overall 60% + Field 30% Thresholds)
- [ ] Update `Assessment` model and `AssessmentController::submit()`:
  - Calculate score percentage for **Overall**, **Strategy**, **Management**, and **Technology**.
  - Add `passed_exam` boolean calculation:
    $$\text{Passed} = (\text{Overall} \ge 60\%) \land (\text{Strategy} \ge 30\%) \land (\text{Management} \ge 30\%) \land (\text{Technology} \ge 30\%)$$
  - Record field breakdown in `assessment.field_performance` or computed accessor.
- [ ] Update `resources/views/assessments/results.blade.php`:
  - Show the 3-field score cards alongside the overall score with clear pass/fail status against the 30% threshold.
  - Explicitly warn if overall score $\ge 60\%$ but failed a field requirement.

### Phase 3: Full Mock Exam & Practice Modes
- [ ] Support 100-question mock exam configuration with a 120-minute timer.
- [ ] Maintain short topic-based practice for quick review sessions.

### Phase 4: Pseudocode Formatting & Programming Logic Questions
- [ ] Add support for code/pseudocode blocks within questions (monospace styled box with syntax preservation).
- [ ] Seed official-style pseudocode practice questions (`FOR/TO`, `IF/ELSE`, loop variable tracing).

---

## 3. Progress Tracker

- [x] Initial outline audit completed and documented in `IT_PASSPORT_ALIGNMENT.md`.
- [ ] Database migration and seeder update for 3 canonical fields.
- [ ] Grading logic update for field minimums.
- [ ] Results view update with field score cards.

# Tests - Voltmont Theme

**Automated testing for trzebnica-elektryk.pl WordPress theme**

---

## 📂 Directory Structure

```
tests/
├── package.json          # Jest configuration and dependencies
├── jest.setup.js         # Global test setup and mocks
├── unit/                 # Unit tests for components
│   ├── navigation.test.js
│   ├── forms.test.js
│   ├── accordion.test.js
│   └── lazy-loading.test.js
└── integration/          # Integration tests
    ├── contact-form.test.js
    └── search.test.js
```

---

## 🚀 Quick Start

### Installation

```bash
cd tests
npm install
```

### Run Tests

```bash
# Run all tests
npm test

# Run in watch mode
npm run test:watch

# Run with coverage
npm run test:coverage

# Run only unit tests
npm run test:unit

# Run only integration tests
npm run test:integration

# Verbose output
npm run test:verbose
```

---

## 📊 Coverage Goals

- **Branches:** 70%+
- **Functions:** 70%+
- **Lines:** 70%+
- **Statements:** 70%+

Coverage reports are generated in `tests/coverage/`

---

## ✅ Test Types

### Unit Tests (`unit/`)
Test individual components in isolation:
- Navigation functionality
- Form validation
- Accordion behavior
- Lazy loading

### Integration Tests (`integration/`)
Test multiple components working together:
- Contact form submission flow
- Search functionality
- User interactions

---

## 🧪 Example Test

```javascript
import { screen } from '@testing-library/dom';
import userEvent from '@testing-library/user-event';

describe('Navigation', () => {
    test('mobile menu toggles', async () => {
        const user = userEvent.setup();
        const toggle = screen.getByLabelText('Toggle menu');
        
        await user.click(toggle);
        
        expect(screen.getByRole('navigation')).toBeVisible();
    });
});
```

---

## 🔧 Debugging Tests

### Enable Debug Mode

```bash
DEBUG=true npm test
```

### Run Single Test File

```bash
npm test -- unit/navigation.test.js
```

### Update Snapshots

```bash
npm test -- -u
```

---

## 📝 Writing Tests

### Best Practices

1. **Arrange-Act-Assert pattern**
2. **Use semantic queries** (`getByRole`, `getByLabelText`)
3. **Test user behavior**, not implementation
4. **Mock external dependencies**
5. **Keep tests simple and focused**

### Example Structure

```javascript
describe('Component Name', () => {
    beforeEach(() => {
        // Setup
    });
    
    afterEach(() => {
        // Cleanup
    });
    
    test('does something', () => {
        // Arrange
        // Act
        // Assert
    });
});
```

---

## 🐛 Common Issues

### Tests Timeout
Increase timeout in `jest.setup.js`:
```javascript
jest.setTimeout(20000);
```

### DOM Not Cleaning Up
Ensure `afterEach` cleanup:
```javascript
afterEach(() => {
    document.body.innerHTML = '';
});
```

### Mock Not Working
Check import order in `jest.setup.js`

---

## 📚 Resources

- [Jest Documentation](https://jestjs.io/)
- [Testing Library](https://testing-library.com/)
- [User Event Guide](https://testing-library.com/docs/user-event/intro)
- [Jest DOM Matchers](https://github.com/testing-library/jest-dom)

---

## 🔗 Related Documentation

- `docs/TESTING_COMPONENTS_GUIDE.md` - Complete testing guide
- `TESTING.md` - Project testing strategy
- `CONTRIBUTING.md` - Contribution guidelines

---

*For PHP testing (PHPUnit), see `docs/TESTING_COMPONENTS_GUIDE.md`*

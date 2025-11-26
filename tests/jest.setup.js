/**
 * Jest Setup Configuration
 * 
 * Global test setup and mocks for Voltmont theme testing
 */

import '@testing-library/jest-dom';

// Mock IntersectionObserver for lazy loading tests
global.IntersectionObserver = class IntersectionObserver {
    constructor(callback) {
        this.callback = callback;
    }
    disconnect() {}
    observe() {
        // Simulate immediate intersection
        this.callback([{ isIntersecting: true, target: {} }]);
    }
    takeRecords() {
        return [];
    }
    unobserve() {}
};

// Mock window.matchMedia for responsive tests
Object.defineProperty(window, 'matchMedia', {
    writable: true,
    value: jest.fn().mockImplementation(query => ({
        matches: false,
        media: query,
        onchange: null,
        addListener: jest.fn(),
        removeListener: jest.fn(),
        addEventListener: jest.fn(),
        removeEventListener: jest.fn(),
        dispatchEvent: jest.fn(),
    })),
});

// Mock localStorage
const localStorageMock = {
    getItem: jest.fn(),
    setItem: jest.fn(),
    removeItem: jest.fn(),
    clear: jest.fn(),
};
global.localStorage = localStorageMock;

// Mock sessionStorage
const sessionStorageMock = {
    getItem: jest.fn(),
    setItem: jest.fn(),
    removeItem: jest.fn(),
    clear: jest.fn(),
};
global.sessionStorage = sessionStorageMock;

// Mock fetch API for AJAX tests
global.fetch = jest.fn(() =>
    Promise.resolve({
        ok: true,
        json: () => Promise.resolve({ success: true }),
        text: () => Promise.resolve(''),
    })
);

// Suppress console output in tests
const originalConsole = { ...console };
global.console = {
    ...console,
    error: jest.fn(),
    warn: jest.fn(),
    log: jest.fn(),
};

// Restore console for debugging if needed
if (process.env.DEBUG === 'true') {
    global.console = originalConsole;
}

// Set test timeout
jest.setTimeout(10000);

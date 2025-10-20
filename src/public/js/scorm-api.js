/**
 * SCORM 1.2 / 2004 API Bridge
 * Provides basic SCORM LMS API for content packages
 */

(function () {
    // Initialize SCORM API
    window.API = window.API || {};
    window.API_1484_11 = window.API_1484_11 || {};

    // SCORM Data Model (in-memory storage)
    const scormData = {
        'cmi.core.student_id': 'user-' + Math.random().toString(36).substr(2, 9),
        'cmi.core.student_name': 'User',
        'cmi.core.lesson_location': '0',
        'cmi.core.lesson_status': 'not attempted',
        'cmi.core.score.raw': '0',
        'cmi.core.score.max': '100',
        'cmi.core.score.min': '0',
        'cmi.core.total_time': '0',
        'cmi.core.lesson_mode': 'normal',
        'cmi.suspend_data': '{}',
        'cmi.launch_data': '',
        'cmi.comments': '',
        'cmi.comments_from_lms': '',
        'cmi.interactions._count': '0'
    };

    // SCORM 2004 Data Model
    const scormData2004 = {
        'cmi.learner_id': 'user-' + Math.random().toString(36).substr(2, 9),
        'cmi.learner_name': 'User',
        'cmi.location': '0',
        'cmi.completion_status': 'not attempted',
        'cmi.success_status': 'unknown',
        'cmi.score.raw': '0',
        'cmi.score.max': '100',
        'cmi.score.min': '0',
        'cmi.total_time': '0',
        'cmi.mode': 'normal',
        'cmi.suspend_data': '{}',
        'cmi.launch_data': '',
        'cmi.comments': '',
        'cmi.interactions._count': '0'
    };

    // API Implementation - SCORM 1.2
    window.API = {
        initialized: false,
        sessionId: 'session-' + Date.now(),

        Initialize: function (param) {
            console.log('[SCORM 1.2] Initialize called:', param);
            this.initialized = true;
            return 'true';
        },

        LMSInitialize: function (param) {
            console.log('[SCORM 1.2] LMSInitialize called:', param);
            this.initialized = true;
            return 'true';
        },

        Terminate: function (param) {
            console.log('[SCORM 1.2] Terminate called:', param);
            this.initialized = false;
            return 'true';
        },

        LMSFinish: function (param) {
            console.log('[SCORM 1.2] LMSFinish called:', param);
            this.initialized = false;
            return 'true';
        },

        GetValue: function (element) {
            console.log('[SCORM 1.2] GetValue:', element);
            const value = scormData[element] || '';
            console.log('[SCORM 1.2] GetValue result:', value);
            return String(value);
        },

        LMSGetValue: function (element) {
            console.log('[SCORM 1.2] LMSGetValue:', element);
            const value = scormData[element] || '';
            return String(value);
        },

        SetValue: function (element, value) {
            console.log('[SCORM 1.2] SetValue:', element, '=', value);
            scormData[element] = String(value);
            return 'true';
        },

        LMSSetValue: function (element, value) {
            console.log('[SCORM 1.2] LMSSetValue:', element, '=', value);
            scormData[element] = String(value);
            return 'true';
        },

        Commit: function (param) {
            console.log('[SCORM 1.2] Commit called');
            return 'true';
        },

        LMSCommit: function (param) {
            console.log('[SCORM 1.2] LMSCommit called');
            return 'true';
        },

        GetLastError: function () {
            return '0';
        },

        LMSGetLastError: function () {
            return '0';
        },

        GetErrorString: function (errorCode) {
            const errors = {
                '0': 'No error',
                '101': 'General Exception',
                '102': 'General Initialization Failure'
            };
            return errors[errorCode] || 'Unknown error';
        },

        LMSGetErrorString: function (errorCode) {
            const errors = {
                '0': 'No error',
                '101': 'General Exception',
                '102': 'General Initialization Failure'
            };
            return errors[errorCode] || 'Unknown error';
        },

        GetDiagnostic: function (errorCode) {
            return 'No diagnostic information';
        },

        LMSGetDiagnostic: function (errorCode) {
            return 'No diagnostic information';
        }
    };

    // API Implementation - SCORM 2004
    window.API_1484_11 = {
        initialized: false,
        sessionId: 'session-' + Date.now(),

        Initialize: function (param) {
            console.log('[SCORM 2004] Initialize called:', param);
            this.initialized = true;
            return 'true';
        },

        Terminate: function (param) {
            console.log('[SCORM 2004] Terminate called:', param);
            this.initialized = false;
            return 'true';
        },

        GetValue: function (element) {
            console.log('[SCORM 2004] GetValue:', element);
            const value = scormData2004[element] || '';
            console.log('[SCORM 2004] GetValue result:', value);
            return String(value);
        },

        SetValue: function (element, value) {
            console.log('[SCORM 2004] SetValue:', element, '=', value);
            scormData2004[element] = String(value);
            return 'true';
        },

        Commit: function (param) {
            console.log('[SCORM 2004] Commit called');
            return 'true';
        },

        GetLastError: function () {
            return '0';
        },

        GetErrorString: function (errorCode) {
            const errors = {
                '0': 'No error',
                '101': 'General Exception',
                '102': 'General Initialization Failure'
            };
            return errors[errorCode] || 'Unknown error';
        },

        GetDiagnostic: function (errorCode) {
            return 'No diagnostic information';
        }
    };

    // Make APIs globally available
    window.SCORMReady = true;

    // Setup error handling
    window.addEventListener('error', function (event) {
        console.error('[SCORM Error] Uncaught Error:', event.error);
        console.error('Message:', event.message);
        console.error('Filename:', event.filename);
        console.error('Line:', event.lineno);
    });

    window.addEventListener('unhandledrejection', function (event) {
        console.error('[SCORM Error] Unhandled Rejection:', event.reason);
    });

    console.log('%c✅ SCORM API Loaded', 'color: green; font-weight: bold;');
    console.log('Available APIs: window.API (SCORM 1.2), window.API_1484_11 (SCORM 2004)');
})();

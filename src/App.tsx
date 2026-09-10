import React, { useState, useRef } from "react";
import { 
  Building2, 
  Users, 
  CalendarCheck, 
  Pill, 
  FileText, 
  Database, 
  ExternalLink, 
  Printer, 
  RefreshCw,
  Sparkles
} from "lucide-react";

export default function App() {
  const [currentPath, setCurrentPath] = useState<string>(
    "/hospital-management-system/public/index.php?route=dashboard"
  );
  const [isIframeLoading, setIsIframeLoading] = useState<boolean>(true);
  const iframeRef = useRef<HTMLIFrameElement>(null);

  const navigateTo = (route: string) => {
    setIsIframeLoading(true);
    const target = `/hospital-management-system/public/index.php?route=${route}`;
    setCurrentPath(target);
    if (iframeRef.current) {
      iframeRef.current.src = target;
    }
  };

  const reloadIframe = () => {
    setIsIframeLoading(true);
    if (iframeRef.current) {
      iframeRef.current.src = iframeRef.current.src;
    }
  };

  return (
    <div className="flex flex-col h-screen w-full bg-slate-100 text-slate-800 font-sans overflow-hidden">
      {/* Top Hospital Control & Navigation Bar */}
      <header className="bg-white border-b border-slate-200 px-4 py-2 flex items-center justify-between shadow-xs z-10">
        <div className="flex items-center gap-3">
          <img
            src="/hospital-management-system/public/assets/images/yasmeen-logo.png"
            alt="Yasmeen Logo"
            className="w-10 h-10 object-contain rounded-sm"
            onError={(e) => {
              // fallback
              (e.target as HTMLImageElement).src =
                "/hospital-management-system/public/assets/images/yasmeen-logo.svg";
            }}
          />
          <div>
            <div className="flex items-center gap-2">
              <span className="font-extrabold text-emerald-800 text-base tracking-wide">
                YASMEEN MATERNITY AND MEDICAL CENTER
              </span>
              <span className="text-xs bg-teal-50 text-teal-700 font-bold px-1.5 py-0.5 rounded border border-teal-200">
                R-85647
              </span>
            </div>
            <div className="text-xs text-slate-500">
              Hospital Management System • Developed_By_DCtechsolutions
            </div>
          </div>
        </div>

        {/* Quick Nav Shortcuts */}
        <nav className="hidden md:flex items-center gap-1">
          <button
            onClick={() => navigateTo("dashboard")}
            className="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
          >
            <Building2 className="w-3.5 h-3.5 text-emerald-700" />
            Dashboard
          </button>
          <button
            onClick={() => navigateTo("patients")}
            className="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
          >
            <Users className="w-3.5 h-3.5 text-emerald-700" />
            Patients
          </button>
          <button
            onClick={() => navigateTo("attendance")}
            className="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
          >
            <CalendarCheck className="w-3.5 h-3.5 text-emerald-700" />
            Attendance
          </button>
          <button
            onClick={() => navigateTo("pharmacy_stock")}
            className="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
          >
            <Pill className="w-3.5 h-3.5 text-emerald-700" />
            Pharmacy & Stock
          </button>
          <button
            onClick={() => navigateTo("reports")}
            className="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
          >
            <FileText className="w-3.5 h-3.5 text-emerald-700" />
            A4 Reports
          </button>
          <button
            onClick={() => navigateTo("backup")}
            className="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
          >
            <Database className="w-3.5 h-3.5 text-emerald-700" />
            SQL Backup
          </button>
        </nav>

        {/* View Controls */}
        <div className="flex items-center gap-2">
          <button
            onClick={reloadIframe}
            title="Refresh Hospital Window"
            className="p-1.5 text-slate-600 hover:bg-slate-100 rounded-md"
          >
            <RefreshCw className="w-4 h-4" />
          </button>
          <a
            href={currentPath}
            target="_blank"
            rel="noreferrer"
            className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-700 text-white hover:bg-emerald-800 rounded-md transition-colors shadow-xs"
          >
            <ExternalLink className="w-3.5 h-3.5" />
            <span>Open in Full Tab</span>
          </a>
        </div>
      </header>

      {/* Main App Iframe Area */}
      <div className="relative flex-1 w-full h-full bg-slate-200">
        {isIframeLoading && (
          <div className="absolute inset-0 flex flex-col items-center justify-center bg-slate-50/90 z-20">
            <div className="w-8 h-8 border-3 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
            <span className="mt-3 text-sm text-slate-600 font-medium">
              Loading Yasmeen Hospital Management System...
            </span>
          </div>
        )}
        <iframe
          ref={iframeRef}
          src={currentPath}
          title="Yasmeen Hospital Management System"
          className="w-full h-full border-0 bg-white"
          onLoad={() => setIsIframeLoading(false)}
        />
      </div>

      {/* Hospital Footer Status Bar */}
      <footer className="bg-white border-t border-slate-200 px-4 py-1.5 flex items-center justify-between text-xs text-slate-500">
        <div className="flex items-center gap-4">
          <span className="flex items-center gap-1.5">
            <span className="w-2 h-2 rounded-full bg-emerald-500"></span>
            Backend: PHP 8.3 & MariaDB/MySQL (InnoDB)
          </span>
          <span className="hidden sm:inline text-slate-400">•</span>
          <span className="hidden sm:inline">
            Address: Chishtia Colony, Raiwind Road Sundar, Lahore (03074246239)
          </span>
        </div>
        <div className="font-semibold text-slate-600">
          Developed_By_DCtechsolutions
        </div>
      </footer>
    </div>
  );
}

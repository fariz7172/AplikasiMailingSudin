const fs = require('fs');
const path = require('path');

const brainPath = 'C:\\Users\\Fariz\\.gemini\\antigravity-ide\\brain';

try {
  const dirs = fs.readdirSync(brainPath);
  const results = [];
  for (const dir of dirs) {
    const fullPath = path.join(brainPath, dir);
    if (!fs.statSync(fullPath).isDirectory()) continue;
    
    const stat = fs.statSync(fullPath);
    const mtime = stat.mtime;
    
    // Format to local date string (UTC+7)
    const mtimeLocal = new Date(mtime.getTime() + 7 * 60 * 60 * 1000);
    const dateStr = mtimeLocal.toISOString().split('T')[0];
    
    if (dateStr === '2026-06-04') {
      const overviewPath = path.join(fullPath, '.system_generated', 'logs', 'overview.txt');
      const transcriptPath = path.join(fullPath, '.system_generated', 'logs', 'transcript.jsonl');
      const walkPath = path.join(fullPath, 'walkthrough.md');
      
      let firstRequest = '';
      let overviewExists = fs.existsSync(overviewPath);
      let transcriptExists = fs.existsSync(transcriptPath);
      
      if (overviewExists) {
        try {
          const content = fs.readFileSync(overviewPath, 'utf8');
          const firstLine = content.split('\n')[0];
          if (firstLine.trim()) {
            const parsed = JSON.parse(firstLine);
            if (parsed && parsed.content) {
              firstRequest = parsed.content;
            }
          }
        } catch (e) {}
      } else if (transcriptExists) {
        try {
          const content = fs.readFileSync(transcriptPath, 'utf8');
          const firstLine = content.split('\n')[0];
          if (firstLine.trim()) {
            const parsed = JSON.parse(firstLine);
            if (parsed && parsed.content) {
              firstRequest = parsed.content;
            }
          }
        } catch (e) {}
      }
      
      let walkTitle = '';
      if (fs.existsSync(walkPath)) {
        try {
          walkTitle = fs.readFileSync(walkPath, 'utf8').split('\n')[0];
        } catch (e) {}
      }
      
      results.push({
        id: dir,
        mtimeLocal: mtimeLocal.toISOString(),
        firstRequest: firstRequest ? firstRequest.substring(0, 300) : '(No request content found)',
        walkthroughTitle: walkTitle || null
      });
    }
  }
  
  results.sort((a, b) => new Date(a.mtimeLocal) - new Date(b.mtimeLocal));
  console.log(JSON.stringify(results, null, 2));
} catch (e) {
  console.error(e);
}

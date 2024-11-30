const fs = require("fs");
const crypto = require("crypto");
const archiver = require("archiver");
const path = require("path");

const outputPath = path.join(__dirname, "/dist/ps_google_analytics.ocmod.zip");

const output = fs.createWriteStream(outputPath);

const archive = archiver("zip", {
  zlib: { level: 4 },
});

output.on("close", function () {
  console.log(`${archive.pointer()} total bytes`);
  console.log("ps_google_analytics.ocmod.zip has been created");

  // Calculate and log MD5 and SHA256 checksums
  calculateChecksums(outputPath);
});

archive.on("warning", function (err) {
  if (err.code === "ENOENT") {
    console.warn("Warning:", err);
  } else {
    throw err;
  }
});

archive.on("error", function (err) {
  throw err;
});

archive.pipe(output);

archive.directory("src/admin/", "admin");
archive.directory("src/catalog/", "catalog");
archive.file("src/install.json", { name: "install.json" });
archive.file("src/installation.txt", { name: "installation.txt" });

archive.finalize();

/**
 * Calculate MD5 and SHA256 checksums for a file.
 * @param {string} filePath - The path to the file.
 */
function calculateChecksums(filePath) {
  const fileBuffer = fs.readFileSync(filePath);

  const md5Hash = crypto.createHash("md5").update(fileBuffer).digest("hex");
  console.log(`MD5 Checksum: ${md5Hash}`);

  const sha256Hash = crypto.createHash("sha256").update(fileBuffer).digest("hex");
  console.log(`SHA256 Checksum: ${sha256Hash}`);
}
